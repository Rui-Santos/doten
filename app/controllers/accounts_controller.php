<?php
//Configure::write('Config.language', 'chi');
 

class AccountsController extends AppController
{
  var $name = 'Accounts';
  var $helpers = array('Html', 'Form', 'Session');
  var $components = array('Session', 'Bitcoin');
  
  function beforeRender()
  {
  	$this -> set('location', 'Accounts');
  	$this -> set('action', $this -> action);

	$user = $this -> Auth -> user();
	if($user)
	  $this -> set('username', $user['User']['username']);
	  
	$btc_balance = $this -> Session -> read('btc_balance');
	$this -> set('btc_balance', $btc_balance);
  }
  
  function beforeFilter()
  {
    if(isset($this -> data['Account']['amount']))
	  $this -> data['Account']['amount'] = round($this -> data['Account']['amount'], 4);
    parent::beforeFilter();
  }

  function index()
  {
	$this->layout = 'account';
	$this -> set('title_for_layout', '帐户总览');
    $user = $this -> Auth -> user();
	$account_data = $this -> Account -> findByUserId($user['User']['id']);

    $account_balance = $this -> updateBalance($account_data['Account']['alipay_account']);
	$account_balance['btc_frozen'] = $account_data['Account']['btc_frozen'];
    //$account_balance = array('confirmed' => 0, 'unconfirmed' => 0, 'btc_frozen' => $account_data['Account']['btc_frozen']);

	$this -> set('account_id', $account_data['Account']['id']);
    $this -> set('account_alipay', $account_data['Account']['alipay_account']);
    $this -> set('account_balance', $account_balance);

	
	$account_id = $account_data['Account']['id'];
	$this -> paginate = array('order' => array('Order.modified' => 'desc'),
							  'recursive' => 0,
	                          'conditions' => array('NOT' => array('Order.status' => array(4, 10)),
													'OR' => array('Order.trader_account_id' => $account_id,
													'Order.account_id' => $account_id)));
	$this -> set('orders', $this -> paginate('Order'));
	
	$this -> paginate = array('order' => array('Order.modified' => 'desc'),
							  'recursive' => 0,
							  'limit' => 5,
	                          'conditions' => array('Order.status' => 4,
													'OR' => array('Order.trader_account_id' => $account_id,
													'Order.account_id' => $account_id)));
	$this -> set('orders_finished', $this -> paginate('Order'));
  }
  
  function myOrders()
  {
  	//$this -> Account -> Order -> recursive = 0;
	//$this -> set('accounts', $this -> paginate());
  }
  
  function mySetting()
  {
	$this -> set('title_for_layout', '设定');
  	$this -> redirect('/Users/changePassword');
  }
  
  function myTrades()
  {
	$this -> set('title_for_layout', '交易历史');
  	$this -> paginate = array('order' => array('Order.price' => 'asc'),
							  'recursive' => 0,
	                          'conditions' => array(array('Order.status' => 4),
													'OR' => array('Order.trader_account_id' => $account_id,
													'Order.account_id' => $account_id)));
	$this -> set('orders', $this -> paginate('Order'));
  }

  /* unify the alipay, account to alipay_account */
  function getNewAddress($alipay)
  {
  	$data = $this -> Account -> findByAlipayAccount($alipay);
  	if(empty($data))
  	  return 0;
  	
  	$data['Account']['bind_address'] = $this -> Bitcoin -> getNewAddress($alipay);
  	if(!$this -> Account -> save($data))
  	  return 0;
  	
  	return $data['Account']['bind_address'];
  }
  
  function transfer()
  {
	$this -> layout = 'btc';
	$this -> set('title_for_layout', '转账');

	if($this -> data)
	{
	
	  $user_data = $this -> Auth -> user();
	  $source_data = $this -> Account -> findByUserId($user_data['User']['id']);
	  $source = $source_data['Account']['alipay_account'];
	  $dest = $this -> data['Account']['dest'];
	  $amount = $this -> data['Account']['amount'];
  	  
	  if(empty($source_data) || empty($dest) || empty($amount))
  	  {
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_TRANSFER_ARGU', TRUE));
  	    return 0;
	  }
  	
  	  $dest_data = $this -> Account -> findByAlipayAccount($dest);
  	
  	  if(empty($dest_data))
	  {
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_TRANSFER_DEST', TRUE));
  	    return 0;
	  }
  	  
  	  /* not enough money */
  	  if($source_data['Account']['btc_balance'] < $amount)
  	    return 0;
		
	  switch($this -> Bitcoin -> transfer($source, $dest, $amount, FALSE))
	  {
	    case 1:
		  $this -> Session -> setFlash(__d('messages', 'ACCOUNTS_TRANSFER_SUCCEED', TRUE));
		  break;
		case 2:
		  $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_TRANSFER_FUND', TRUE));
		  break;
		case 0:
		  $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_TRANSFER_FAILED', TRUE));
		  break;
		default:
		  break;
		}

	  /* this is not that robust */
	  $dest_balance = $this -> updateBalance($dest);
  	  $source_balance = $this -> updateBalance($source);
	}

  	  
  	return 1;
  }
  
  function withdraw()
  {
	$this -> layout = 'btc';
	$this -> set('title_for_layout', '提现');
	if($this -> data)
	{
	  $user = $this -> Auth -> user();
	  $account_data = $this -> Account -> findByUserId($user['User']['id']);
	  $account = $account_data['Account']['alipay_account'];
	  $address = $this -> data['Accounts']['address'];
	  $amount = $this -> data['Accounts']['amount'];
	
  	  if(empty($account) || empty($address) || empty($amount))
	  {
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_WITHDRAW_ARGU', TRUE));
  	    return 0;
	  }
  	  
  	  if(!$this -> Bitcoin -> verify($address))
	  {
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_WITHDRAW_DEST', TRUE));
  	    return 0;
	  }
  	
  	  if($account_data['Account']['btc_balance'] < $amount)
	  {
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_WITHDRAW_FUND', TRUE));
  	    return 0;
	  }
  	
  	  $txid = $this -> Bitcoin -> withdraw($account, $address, $amount - 0.0005);
  	
  	  $account_balance = $this -> updateBalance($account);
  	
  	  if(!empty($txid))
	    $this -> Session -> setFlash(__d('messages', 'ACCOUNTS_WITHDRAW_SUCCEED', TRUE));
	  else
	    $this -> Session -> setFlash(__d('errors', 'ACCOUNTS_WITHDRAW_FAILED', TRUE));
	}
  }
  function deposit()
  {
	$this -> layout = 'btc';
	$this -> set('title_for_layout', '充值');
	
	$user_data = $this -> Session -> read('user_data');
	$account_data = $this -> Account -> findByUserId($user_data['User']['id']);
	
	if(!$account_data['Account']['bind_address'])
	{
      $account_data['Account']['bind_address'] = $this -> Bitcoin-> getAddress($account_data['Account']['alipay_account']);
	  if(!$account_data['Account']['bind_address'])
	    $account_data['Account']['bind_address'] = $this -> Bitcoin-> getNewAddress($account_data['Account']['alipay_account']);
      
	  $this -> Account -> save($account_data);
	}
	
	$this -> set('bind_address', $account_data['Account']['bind_address']);
  }
  
  function updateBalance($account)
  {
  	if(empty($account))
  	  return 0;
  	
  	$data = $this -> Account -> findByAlipayAccount($account);
  	if(empty($data))
  	  return 0;
  	  
  	$result = $this -> Bitcoin -> getBalance($account);
  	if(empty($result))
  	  return 0;
  	
  	$data['Account']['btc_balance'] = $result['confirmed'] - $data['Account']['btc_frozen'];
  	if(!$this -> Account -> save($data))
  	  return 0;
	  
	$result['confirmed'] -= $data['Account']['btc_frozen'];
	$this -> Session -> write('btc_balance', (double)$result['confirmed']);
	
  	return $result;
  }
  
  function reset_all()
  {
    $this -> autoRender = false;
	
	$data = $this -> Account -> findAll();
	
	print_r($data);
	
	foreach($data as $account)
	{
	}
  }

}
?>