<?php
class OrdersController extends AppController {
 
  var $name = 'Orders';
	
  var $helpers = array('Html', 'Form', 'Session');
  
  var $components = array('Session', 'Mailer', 'Bitcoin');
	
  function beforeRender()
  {
  	$this -> set('location', 'Orders');
  	$this -> set('action', $this -> action);
  	/*
  	if($this -> action == 'match' || $this -> action == 'payer_confirm' || $this -> action == 'payee_confirm' || $this -> action == 'cancel')
  	  $this -> redirect($this -> referer());
	*/
	$user_data = $this -> Auth -> user();
	if($user_data)
	{
	  $this -> set('username', $user_data['User']['username']);
	  $this -> set('account_id', $user_data['User']['account_id']);
	}
	
	$btc_balance = $this -> Session -> read('btc_balance');
	$this -> set('btc_balance', $btc_balance);
  }

  function beforeFilter()
  {
    
    if(isset($this -> data['Order']['amount']))
	  $this -> data['Order']['amount'] = round($this -> data['Order']['amount'], 4);

    $this->Auth->allow('index');
    parent::beforeFilter();
  }
  
	function index()
	{
	  $this->layout = 'market';
	  $this -> set('title_for_layout', '市场总览');
	  $this -> paginate = array('order' => array('Order.price' => 'asc'),
								'recursive' => 0,
								'conditions' => array('Order.status' => 0, 'Order.type' => 'sell'));
	  $this -> set('orders_sell', $this -> paginate('Order'));
	  
	  $this -> paginate = array('order' => array('Order.price' => 'desc'),
								'recursive' => 0,
								'conditions' => array('Order.status' => 0, 'Order.type' => 'buy'));
	  $this -> set('orders_buy', $this -> paginate('Order'));
	}
	
	function contract($order_id = NULL)
	{
	  $this -> set('title_for_layout', '交易合同');
	  $this->layout = 'btc';
	  if(!$order_id)
	    $this -> redirect($this -> referer());
		
	  $order_data = $this -> Order -> findById($order_id);
	  if($order_data['Order']['status'] != 1 && $order_data['Order']['status'] != 2)
	    $this -> redirect($this -> referer());
		
	  $trader_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
	  $this -> set('alipay_account', $order_data['Account']['alipay_account']);
	  $this -> set('type', $order_data['Order']['type']);
	  $this -> set('amount', $order_data['Order']['amount']);
	  $this -> set('price', $order_data['Order']['price']);
	  $this -> set('trader_alipay_account', $trader_data['Account']['alipay_account']);
	}
	
	function buy()
	{
		$this -> set('title_for_layout', '全部买单');
	  $this -> Order -> recursive = 0;
	  $this -> set('orders', $this -> paginate());

	}
	
	function sell()
	{
		$this -> set('title_for_layout', '全部卖单');
	  $this -> Order -> recursive = 0;
	  $this -> set('orders', $this -> paginate());
	}
	
	function finished()
	{	
		$this->layout = 'market';
		$this -> set('title_for_layout', '历史交易');
		
		$this -> paginate = array('order' => array('Order.modified' => 'desc'),
								  'recursive' => 0,
								  'conditions' => array('Order.status' => 4, 'Order.type' => 'buy'));
		$this -> set('orders_buy', $this -> paginate('Order'));
		
		$this -> paginate = array('order' => array('Order.modified' => 'desc'),
								  'recursive' => 0,
								  'conditions' => array('Order.status' => 4, 'Order.type' => 'sell'));
		$this -> set('orders_sell', $this -> paginate('Order'));
	}

	function view($order_id = NULL)
	{
	  $this -> set('title_for_layout', '交易详情');
	  $this->layout = 'btc';
	  
	  if(!$order_id)
	    $this -> redirect($this -> referer());
		
	  $order_data = $this -> Order -> findById($order_id);
	  if($order_data['Order']['status'] == 0 || $order_data['Order']['status'] == 10)
	    $this -> redirect($this -> referer());
		
	  $trader_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
	  $this -> set('alipay_account', $order_data['Account']['alipay_account']);
	  $this -> set('created', $order_data['Order']['created']);
	  $this -> set('modified', $order_data['Order']['modified']);
	  $this -> set('type', $order_data['Order']['type']);
	  $this -> set('amount', $order_data['Order']['amount']);
	  $this -> set('price', $order_data['Order']['price']);
	  $this -> set('trader_alipay_account', $trader_data['Account']['alipay_account']);
	}

	function add()
	{
		$this->layout = 'add';
		$this -> set('title_for_layout', '新订单');
		  if($this -> data)
		  {
		    $user = $this -> Auth -> user();
		    $account_data = $this -> Order -> Account -> findByUserId($user['User']['id']);
			
		  	$this -> data['Order']['account_id'] = $account_data['Account']['id'];
		  	$this -> data['Order']['status'] = 0;
		  	
			  if($this -> Order -> add($this -> data))
			  {
			    $this -> Session -> setFlash(__d('messages', 'ORDERS_ADD_SUCCEED', TRUE));
				$this -> redirect(array('controller' => 'Accounts', 'action' => 'index'));
			  }
			  else
				$this -> Session -> setFlash(__d('errors', 'ORDERS_ADD_FAILED', TRUE));
		  }
	}
	
	function match($order_id = NULL)
	{
	  $user_data = $this -> Session -> read('user_data');
	  $account_id = $user_data['Account']['id'];
	  
      if($this -> Order -> match($order_id, $account_id))
      {
	    $order_data = $this -> Order -> findById($order_id);
        /* matched successfully, now send the trade notice mail */
		$seller = array();
		$buyer = array();
		if($order_data['Order']['type'] == 'sell')
		{
		  $seller_data = $this -> Order -> Account -> findById($order_data['Order']['account_id']);
		  $seller['alipay_account'] = $seller_data['Account']['alipay_account'];
		  $seller['username'] = $seller_data['User']['username'];
		  $buyer_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
		  $buyer['alipay_account'] = $buyer_data['Account']['alipay_account'];
		  $buyer['username'] = $buyer_data['User']['username'];
		}
		else
		{
		  $buyer_data = $this -> Order -> Account -> findById($order_data['Order']['account_id']);
		  $buyer['alipay_account'] = $buyer_data['Account']['alipay_account'];
		  $buyer['username'] = $buyer_data['User']['username'];
		  $seller_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
		  $seller['alipay_account'] = $seller_data['Account']['alipay_account'];
		  $seller['username'] = $seller_data['User']['username'];
		}
		
		$this -> Mailer -> sendTradeNoticeMail(array('Mailer' => array(
														'username' => $buyer['username'],
														'conf_time' => date('Y-m-d H:i:s'),
														'order_id' => $order_id,
														'price' => $order_data['Order']['price'],
														'amount' => $order_data['Order']['amount'],
														'total' => $order_data['Order']['price'] * $order_data['Order']['amount'],
														'buyer_alipay_account' => $buyer['alipay_account'],
														'seller_alipay_account' => $seller['alipay_account'])));
														
		/* go to the contract */
		$this -> redirect(array('action' => 'contract', $order_id));
      }
      else
	  {
        $this -> Session -> setFlash(__d('errors', 'ORDERS_MATCH_FAILED', TRUE));
		$this -> redirect($this -> referer());
	  }

	  /* save the status here, if necessary */
	}

  function payer_confirm($order_id = NULL)
	{
		$user = $this -> Auth -> user();
		$account_data = $this -> Order -> Account -> findByUserId($user['User']['id']);
		$account_id = $account_data['Account']['id'];
		
		if($order_id)
		{
			if($this -> Order -> payer_confirm($order_id, $account_id))
			{
				/* payer_confirm succeed, now set flash */
				$this -> Session -> setFlash(__d('messages', 'ORDERS_PAYERCONF_SUCCEED', TRUE));
				
				$order_data = $this -> Order -> findById($order_id);
				/* matched successfully, now send the trade notice mail */
				$seller = array();
				$buyer = array();
				if($order_data['Order']['type'] == 'sell')
				{
				  $seller_data = $this -> Order -> Account -> findById($order_data['Order']['account_id']);
				  $seller['alipay_account'] = $seller_data['Account']['alipay_account'];
				  $seller['username'] = $seller_data['User']['username'];
				  $buyer_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
				  $buyer['alipay_account'] = $buyer_data['Account']['alipay_account'];
				  $buyer['username'] = $buyer_data['User']['username'];
				}
				else
				{
				  $buyer_data = $this -> Order -> Account -> findById($order_data['Order']['account_id']);
				  $buyer['alipay_account'] = $buyer_data['Account']['alipay_account'];
				  $buyer['username'] = $buyer_data['User']['username'];
				  $seller_data = $this -> Order -> Account -> findById($order_data['Order']['trader_account_id']);
				  $seller['alipay_account'] = $seller_data['Account']['alipay_account'];
				  $seller['username'] = $seller_data['User']['username'];
				}
				
				$this -> Mailer -> sendPaymentNoticeMail(array('Mailer' => array(
														'username' => $seller['username'],
														'conf_time' => date('Y-m-d H:i:s'),
														'order_id' => $order_id,
														'price' => $order_data['Order']['price'],
														'amount' => $order_data['Order']['amount'],
														'total' => $order_data['Order']['price'] * $order_data['Order']['amount'],
														'buyer_alipay_account' => $buyer['alipay_account'],
														'seller_alipay_account' => $seller['alipay_account'])));
			}
			else
				$this -> Session -> setFlash(__d('errors', 'ORDERS_PAYERCONF_FAILED', TRUE));
		}
		/* save the status here, if necessary */
		
		$this -> redirect($this -> referer());
	}
	
	function payee_confirm($order_id = NULL)
	{
		$user = $this -> Auth -> user();
		$my_account_data = $this -> Order -> Account -> findByUserId($user['User']['id']);
		$my_account_id = $my_account_data['Account']['id'];
		$order_data = $this -> Order -> findById($order_id);
		
		if($order_data)
		{
			$rr = $this -> Order -> payee_confirm($order_id, $my_account_id);
			if($rr)
			{
				/* payee_confirm succeed, now go to pay */
				if($order_data['Order']['account_id'] == $my_account_id)
				  $dest_account_id = $order_data['Order']['trader_account_id'];
				else
				  $dest_account_id = $order_data['Order']['account_id'];
				  
				$dest_account_data = $this -> Order -> Account -> findById($dest_account_id);
				$this -> Bitcoin -> transfer($my_account_data['Account']['alipay_account'], $dest_account_data['Account']['alipay_account'], (double)$order_data['Order']['amount'], TRUE);
				$this -> Session -> setFlash(__d('messages', 'ORDERS_PAYEECONF_SUCCEED', TRUE) . $rr);
			}
			else
				$this -> Session -> setFlash(__d('errors', 'ORDERS_PAYEECONF_FAILED', TRUE));
		}

		/* save the status here, if necessary */
		
		$this -> redirect($this -> referer());
	}

	function cancel($order_id = NULL)
	{
		if($order_id)
		{
			$user_data = $this -> Auth -> user();
			$account_data = $this -> Order -> Account -> findByUserId($user_data['User']['id']);
			$ii = $this -> Order -> cancel($order_id, $account_data['Account']['id']);
			if($ii)
			{
				/* canceled, now setFlash */
				$this -> Session -> setFlash(__d('messages', 'ORDERS_CANCEL_SUCCEED', TRUE));
			}
			else
				$this -> Session -> setFlash(__d('errors', 'ORDERS_CANCEL_FAILED', TRUE));
	  }
		/* save the status here, if necessary */
		$this -> redirect($this -> referer());
	}

	function btcc_output()
	{
	
	$this -> autoRender = false;
	
	$output_mtime = filemtime('../webroot/btcc_output');
	if(time() - $output_mtime < 300)
	{
		echo file_get_contents('../webroot/btcc_output');
		return;
	}
	
	$condition = array('order' => array('Order.modified' => 'desc'),
								  'recursive' => 0,
								  'conditions' => array("Order.modified >" => date('Y-m-d', time()) . ' 00:00:00'));
	$result = $this -> Order -> find('all', $condition);
	
	$condition_yestrd = array('order' => array('Order.modified' => 'desc'),
								  'recursive' => 0,
								  'conditions' => array("Order.modified >" => date('Y-m-d', time() - 86400) . ' 00:00:00'));
	$result_yestrd = $this -> Order -> find('first', $condition_yestrd);
	
	$output = array();
	$output['symbol'] = 'doTen';
	$output['currency'] = 'CNY';
	$output['bid'] = 0;
	$output['ask'] = 0;
	$output['lastest_trade'] = strtotime($result[0]['Order']['modified']);
	$output['n_trade'] = count($result);
	$output['open'] = $result[count($result) - 1]['Order']['price'];
	$output['high'] = 0;
	$output['low'] = 0;
	$output['close'] = $result[0]['Order']['price'];
	$output['previous_close'] = $result_yestrd['Order']['price'];
	$output['volume'] = 0;
	$output['currency_volume'] = 0;
	
	$i = 0;
	foreach($result as $order)
	{
		if($order['Order']['price'] > $output['bid'])
			$bid = $order['Order']['price'];
		if($order['Order']['price'] < $output['ask'] || $output['ask'] == 0)
			$output['ask'] = $order['Order']['price'];
		
		if($order['Order']['price'] > $output['high'] && $order['Order']['status'] == 4)
			$output['high'] = $order['Order']['price'];
		if($order['Order']['price'] < $output['low'] || $output['low'] == 0  && $order['Order']['status'] == 4)
			$output['low'] = $order['Order']['price'];
		
		$output['volume'] += $order['Order']['amount'];
		$output['currency_volume'] += $order['Order']['amount'] * $order['Order']['price'];
		
		$i++;
	}
	
	$output = json_encode($output);
	$fp = fopen('../webroot/btcc_output', 'w');
	fwrite($fp, $output);
	
	echo $output;
	}
	
	function api_trades()
	{
	
		$this -> autoRender = false;
	
		$output_mtime = filemtime('../webroot/api_trades');
		if(time() - $output_mtime < 300)
		{
			echo file_get_contents('../webroot/api_trades');
			return;
		}
	
		$condition = array('order' => array('Order.modified' => 'desc'),
											'recursive' => 0,
											'conditions' => array('Order.status' => 4));
		$result = $this -> Order -> find('all', $condition);

	
		$output = '[';
		$temp_data = array();
			
		$i = 0;
		foreach($result as $order)
		{
			$temp_data['date'] = strtotime($order['Order']['modified']);
			$temp_data['price'] = $order['Order']['price'];
			$temp_data['amount'] = $order['Order']['amount'];
			$temp_data['tid'] = $order['Order']['id'];
				
			if($i != 0)
				$output .= ',';
			$output .= json_encode($temp_data);
				
			$i++;
		}
		$output .= ']';
		
		$fp = fopen('../webroot/api_trades', 'w');
		fwrite($fp, $output);
	
		echo $output;
	}
	
	function api_depth()
	{
	
		$this -> autoRender = false;
	
		$output_mtime = filemtime('../webroot/api_depth');
		if(time() - $output_mtime < 300)
		{
			echo file_get_contents('../webroot/api_depth');
			return;
		}
	
		$condition = array('order' => array('Order.modified' => 'desc'),
											'recursive' => 0,
											'conditions' => array('Order.type' => 'sell', 'Order.modified >' => date('Y-m-d', time()) . ' 00:00:00'));
		$result = $this -> Order -> find('all', $condition);

	
		$output = array();
		
		$i = 0;
		foreach($result as $order)
		{
			$output['ask'][$i][0] = $order['Order']['price'];
			$output['ask'][$i][1] = $order['Order']['amount'];
			$i++;
		}
		
		$condition = array('order' => array('Order.modified' => 'desc'),
											'recursive' => 0,
											'conditions' => array('Order.type' => 'buy', 'Order.modified >' => date('Y-m-d', time()) . ' 00:00:00'));
		$result = $this -> Order -> find('all', $condition);
		
		$i = 0;
		foreach($result as $order)
		{
			$output['bid'][$i][0] = $order['Order']['price'];
			$output['bid'][$i][1] = $order['Order']['amount'];
			$i++;
		}
		
		$output = json_encode($output);
		$fp = fopen('../webroot/api_depth', 'w');
		fwrite($fp, $output);
	
		echo $output;
	}
	
	
	function check($token = NULL)
	{
	  $this -> autoRender = false;
	  
	  if(empty($token) || $token != '3322')//md5(date('H:i:s')))
	  {
	    header('HTTP/1.0 404 Not Found');
		return 0;
	  }
	  
	  /* no payer confirmation over 15mins */
	  $condition_00 = array("Order.status" => 1, "Order.modified <" => date('Y-m-d H:i:s', strtotime('-60 minutes', time())));
	  /* no payee confirmation over 12hrs */
	  $condition_01 = array("Order.status" => 2, "Order.modified <" => date('Y-m-d H:i:s', strtotime('-12 hours', time())));
	  
	  $result_00 = $this -> Order -> find('all', $condition_00);
	  //$result_01 = $this -> Order -> find('all', $condition_01);
	  /*
	  foreach($result_00 as $order)
	    $this -> Order -> reset($order['Order']['id']);
	  */
	  //foreach($result_01 as $order)
	  //  $this -> Order -> payee_confirm($order['Order']['id']);
		
//	  if($this -> Order -> save($result_00) && $this -> Order -> save($result_01))
	    echo 'ok';
//	  else
//	    echo 'fail';
	}
}
