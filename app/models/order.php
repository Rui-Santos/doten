<?php

//  Order Class
//  status:

//    0 - to be matched
//    1 - matched
//    2 - confirmed by payer
//    3 - confirmed by payee
//    4 - finished
//    10 - canceled or deleted


class Order extends AppModel
{
  var $name = 'Order';

	var $belongsTo = array('Account' => array(
                           'className' => 'Account',
                           'conditions' => '',
												   'order' => '',
                           'foreignKey' => 'account_id')
                        );
						
	var $validate = array (
	'id' => array (
      'rule' => 'numeric',
      'message' => '嘿..你到底想干什么!'
    ),
    'price' => array (
      'rule' => 'numeric',
      'message' => '嘿..其他的什么字符可不能填在这.'
    ),
    'amount' => array (
      'rule' => 'numeric',
      'message' => '呃..这必须填一个数字.'
    ),
  );
  
  function beforeSave()
  {
	/* tx fee, need to get it into the config */
  	if($this -> data['Order']['price'] <= 0 || $this -> data['Order']['amount'] <= 0)
  	  return 0;

  	return 1;
  }
                        
  function save($data = NULL, $validate = TRUE, $fieldList = array())
  {
    //clear modified field value before each save
    if (isset($this -> data) && isset($this -> data[$this -> name]))
			unset($this -> data[$this -> name]['modified']);
		
		if (isset($data) && isset($data[$this -> name]))
			unset($data[$this -> name]['modified']);
		
		return parent::save($data, $validate, $fieldList);
	}

/*  override the delete fucntion
    no need	
  function delete($order_id = NULL)
  {
    if(!$order_id)
	  return 0;
	  
	$order_data = $this -> findById($order_id);
	if(!$order_data)
	  return 0;
	
	$order_data['Order']['id'] = 10;
	$this -> save($order_data);
  }
*/             

	public function add($data = NULL)
	{
		if($data)
		{
		  $this -> create();
		  
		  if($data['Order']['type'] == 'sell')
		  {
		  	$account_data = $this -> Account -> findById($data['Order']['account_id']);
		  	
		  	/* is there enough btc in owner`s account? */
		  	/* better check in the account controller callback function */
		  	//if($account_data['Account']['btc_balance'] < $data['Order']['amount'])
		  	//  return 0;
		  	$account_data['Account']['btc_balance'] -= (double)$data['Order']['amount'];
			$account_data['Account']['btc_frozen'] += (double)$data['Order']['amount'];
			  
			if(!$this -> Account -> save($account_data))
			  return 0;
		  }

		  if(!$this -> save($data))
		    return 0;
		}
		
    return 1;
	}
	
	public function reset($order_id = NULL)
	{
		$order_data = $this -> findById($order_id);
		
		if(empty($order_data))
		  return 0;

		/* only when the status is matched can be reset */
		if($order_data['Order']['status'] != 1)
		  return 0;

		if($order_data['Order']['type'] == 'buy')
		{
			$trader_data = $this -> Account -> findById($order_data['Order']['trader_account_id']);
			$trader_data['Account']['btc_balance'] += (double)$order_data['Order']['amount'];
			$trader_data['Account']['btc_frozen'] -= (double)$order_data['Order']['amount'];
			
			if(!$this -> Account -> save($trader_data))
			  return 0;
		}
		  
		/* clear the trader_account_id and status */
		$order_data['Order']['status'] = 0;
		$order_data['Order']['trader_account_id'] = 0;

		if(!$this -> save($order_data))
		  return 0;
		
    return 1;
	}
	
	/*                 canceling map
	    
		  status      type     owner    I CAN CANCEL
		
		
		  normal       *       myself       Y
		  
		                       myself       N
		              sell     
		                       other        Y
		  matched
		                       myself       Y
					  buy
		                       other        N
    */
	
	public function cancel($order_id = NULL, $account_id = NULL)
	{
		$order_data = $this -> findById($order_id);

		switch($order_data['Order']['status'])
		{
		  /* the order status is normal and the owner send this request */
		  case 0:
		    if($order_data['Order']['account_id'] != $account_id)
			  return 0;
		    break;
		  
		  /* the order status is matched and the trader send this request */
		  case 1:
		    if(!(($order_data['Order']['type'] == 'sell' && $order_data['Order']['trader_account_id'] == $account_id) || ($order_data['Order']['type'] == 'buy' && $order_data['Order']['account_id'] == $account_id)))
		      return 0;
		    break;
		      
		  default:
		    return 0;
		    break;
		}
		
		/* first, if it is a NOT MATCHED sell order, and owner requests, de-freeze owner`s balance */
		if($order_data['Order']['type'] == 'sell' && $order_data['Order']['status'] == 0 && $order_data['Account']['id'] == $account_id)
		{
			$order_data['Account']['btc_balance'] += (double)$order_data['Order']['amount'];
			$order_data['Account']['btc_frozen'] -= (double)$order_data['Order']['amount'];
			
			/* save changes */		
			if(!$this -> Account -> save($order_data))
			  return 0;
		}

		/* second, reset the order, only when it is matched */
		if($order_data['Order']['status'] == 1)
		  if(!$this -> reset($order_id))
		    return 0;
		  else
		    $order_data = $this -> findById($order_data['Order']['id']);

		/* owner requests, delete */
		if($order_data['Account']['id'] == $account_id)
		{
		  $order_data['Order']['status'] = 10;
	      
		  if(!$this -> save($order_data))
		    return 0;
		}
		
    return 1;
	}

  public function match($order_id = NULL, $trader_account_id = NULL)
  {
    /* locate the order to update */
		$matched_order = $this -> findById($order_id);
		
		/* order status check */
		if($matched_order['Order']['status'] != 0)
		  return 0;
		  
		/* are you insane to match ur own order? */
		if($matched_order['Order']['account_id'] == $trader_account_id)
		  return 0;

		/* found or not */
		if(!empty($matched_order))
		{
			if($matched_order['Order']['type'] == 'buy')
			{
			  /* first, freeze some of the trader`s btc */
			  $trader_data = $this -> Account -> findById($trader_account_id);
			  
			  $trader_data['Account']['btc_balance'] -= (double)$matched_order['Order']['amount'];
			  $trader_data['Account']['btc_frozen'] += (double)$matched_order['Order']['amount'];
			  
			  if(!$this -> Account -> save($trader_data))
			    return 0;
			}
			
			/* second, update the order */
			$matched_order['Order']['trader_account_id'] = $trader_account_id;
			$matched_order['Order']['status'] = 1;
			
			if(!$this -> saveAll($matched_order))
			  return 0;
		}
		else
		  return 0;
		  
		return 1;
  }
  
  public function payer_confirm($order_id = NULL, $trader_account_id = NULL)
  {
  	/* locate the order to update */
  	$order_data = $this -> findById($order_id);
		
	/* order trader check */
	/* the buyer is the payer */
	if($order_data['Order']['type'] == 'buy')
	{
	  if($trader_account_id != $order_data['Order']['account_id'])
		return 0;
	}
	else
	{
	  if($trader_account_id != $order_data['Order']['trader_account_id'])
		return 0;
	}
		  
	/* order status check */
	if($order_data['Order']['status'] != 1)
	  return 0;
		
	/* update the order */
  	$order_data['Order']['status'] = 2;
	if(!$this -> save($order_data))
	  return 0;
		
	return 1;
  }
  
  public function payee_confirm($order_id = NULL, $trader_account_id = NULL)
  {
  	/* locate the order to update */
  	$order_data = $this -> findById($order_id);
		
	/* order trader check */
	/* the seller is the payee */
	if($order_data['Order']['type'] == 'buy')
	{
	  if($trader_account_id != $order_data['Order']['trader_account_id'])
		return 0;
	}
	else
	{
	  if($trader_account_id != $order_data['Order']['account_id'])
		return 0;
	}
		
	/* order status check */
	if($order_data['Order']['status'] != 2)
	  return 0;
		
	/* update the order */
	$order_data['Order']['status'] = 3;
	if(!$this -> save($order_data))
	{
	  error_log(date('Y-m-d H:i:s') . " 2 order.php\n", 3, '/var/log/php_bitcoin.log');
	  return 0;
	}

	if(!$this -> finish($order_id))
	{
	  error_log(date('Y-m-d H:i:s') . " 3 order.php\n", 3, '/var/log/php_bitcoin.log');
	  return 0;
	}
		
	return 1;
  }
  
  public function finish($order_id = NULL)
  {
  	/* locate the order to update */
  	$order_data = $this -> findById($order_id);
				
	/* order status check */
	if($order_data['Order']['status'] != 3)
	  return 0;

    if($order_data['Order']['type'] == 'buy')
    {
      /* first, take the trader`s forzen btc and give them to the owner */
	  $trader_data = $this -> Account -> findById($order_data['Order']['trader_account_id']);
	  $order_data['Account']['btc_balance'] += (double)$order_data['Order']['amount'];
	  $trader_data['Account']['btc_frozen'] -= (double)$order_data['Order']['amount'];
		  
	  if(!$this -> Account -> save($trader_data))
		return 0;
    }
    else
    {
	  /* first, take the owner`s forzen btc and give them to the trader */
	  $order_data['Account']['btc_frozen'] -= (double)$order_data['Order']['amount'];

	  $trader_data = $this -> Account -> findById($order_data['Order']['trader_account_id']);
	  $trader_data['Account']['btc_balance'] += $order_data['Order']['amount'];
	  if(!$this -> Account -> save($trader_data))
		return 0;
	}
	  		
	/* second, update the order */
	$order_data['Order']['status'] = 4;
	if(!$this -> saveAll($order_data))
	  return 0;
		  
	return 1;
  }

}

?>