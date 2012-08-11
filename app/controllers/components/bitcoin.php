<?php
 
require 'jsonRPCClient.php';
require 'verify_addr.php';

define('BTC_PROTOCOL', 'http');
define('BTC_USER', '');
define('BTC_PASS', '');
define('BTC_SERVER', 'localhost');
define('BTC_PORT', '5555');

define('BTC_TXFEE', 0.0005);
//define('BTC_FEE_RATE', 0.005);
define('BTC_FEE_RATE', 0);
define('BTC_FEE_ACCOUNT', 'doTen@doTen.co');
  
define('BTC_TEST_ACCOUNT', 'daddy');

define('PHP_BITCOIN_LOG', '/var/log/php_bitcoin.log');


class BitcoinComponent extends Object
{
  public $conn;
  
  private $uri;
  private $scheme;
  private $username;
  private $password;
  private $server;
  private $port;


  function initialize(&$controller, $settings = array())
  {
    $this -> scheme = strtolower(BTC_PROTOCOL);
		$this -> username = BTC_USER;
		$this -> password = BTC_PASS;
		$this -> server = BTC_SERVER;
		$this -> port = BTC_PORT;
    
    /* Scheme must be http or https */
    if($this -> scheme != "http" && $this -> scheme != "https")
      return 0;
    
    /* Username must be non-blank */
    if(empty($this -> username))
      return 0;
    
    /* Password must be non-blank */
    if(empty($this -> password))
      return 0;
        
    $this -> port = (string)($this -> port);
    	
    $this -> uri = $this -> scheme . "://" . $this -> username . ":" . $this -> password . "@" . $this -> server . ":" . $this -> port . "/";

    // saving the controller reference for later use
    $this -> controller =& $controller;
  }
	
	function connect()
	{
	
	  $i = 0;
	  do
	  {
	    $try_again = false;
	    try
	    {
          $this -> conn = new jsonRPCClient($this -> uri);
        }
		catch(Exception $e)
		{
		  $try_again = true;
		  /* sleep 0.2 sec */
		  usleep(20000);
		}
        $i++;
	  }
	  while($try_again && $i < 5);

      if(!$this -> conn)
        return 0;
	}
	
	function getBalance($account = NULL)
	{
		if(!$this -> conn)
			$this -> connect();
		
		if(empty($account))
		  return 0;
		  
		$result = array('confirmed' => 0, 'unconfirmed' => 0);
		$result['confirmed'] = $this -> conn -> getbalance($account, 6);
		$result['unconfirmed'] = $this -> conn -> getbalance($account, 0) - $result['confirmed'];
		
		if(round($result['confirmed'], 4) > $result['confirmed'])
	      $result['confirmed'] = round($result['confirmed'], 4) - 0.0001;
		else
		  $result['unconfirmed'] = round($result['unconfirmed'], 4);
	  
		if(round($result['unconfirmed'], 4) > $result['unconfirmed'])
	      $result['unconfirmed'] = round($result['unconfirmed'], 4) - 0.0001;
		else
		  $result['unconfirmed'] = round($result['unconfirmed'], 4);
    
		return $result;
	}
	
	function transfer($source = NULL, $dest = NULL, $amount = 0, $fee = 0)
	{
		if(!$this -> conn)
		  $this -> connect();
		  
		if(empty($source) || empty($dest))
		  return 0;
		
		if($amount == 0)
		  return 0;
		  
		/* check if there is enough */
		$result = $this -> getBalance($source);
		if($result['confirmed'] < $amount)
		  return 2;

		if($fee == 1)
		  $fee_amount = (double)($amount * (double)BTC_FEE_RATE);
		else
		  $fee_amount = 0;
		$amount -= (double)$fee_amount;

		$move_result = $this -> conn -> move($source, $dest, (double)$amount);
		error_log(date('Y-m-d H:i:s') . ' ' . $move_result . ' ' . $source . ' ' . $dest . ' ' . (double)$amount . "\n", 3, PHP_BITCOIN_LOG);
		if($move_result == 0)
		  return 0;

		return 1;
	}
	
	function getNewAddress($account)
	{
		if(!$this -> conn)
		  $this -> connect();
		
		if(empty($account))
		  return 0;
		  
		return $this -> conn -> getnewaddress($account);
	}
		
  function getAddress($account)
  {
		if(!$this -> conn)
		  $this -> connect();
		
		if(empty($account))
		  return 0;
		  
		return $this -> conn -> getaccountaddress($account);
	}
	
	function withdraw($account, $address, $amount)
  {
  	if(!$this -> conn)
		  $this -> connect();
		
	if(empty($account) || empty($address) || empty($amount))
	  return 0;
	  
	/* check if there is enough */
	$result = $this -> getBalance($account);
	if($result['confirmed'] < $amount + BTC_TXFEE)
	  return 0;
		  
	return $this -> conn -> sendfrom($account, $address, (double)$amount);
  }
  
  function verify($address)
  {
  	return checkAddress($address);
  }
  
}

?>
