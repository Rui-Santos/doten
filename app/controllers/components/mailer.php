 <?php

define('SMTP_SERVER', '');
define('SMTP_PORT', '465');
define('SMTP_TIMEOUT', '30');
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_SENDER', '');


class MailerComponent extends Object
{
	var $controller;
	
	var $components = array('Email', 'Session');
	
	var $host = 'smtp.gmail.com';
	
	var $port = '465';
	
	var $timeout = '30';
	
	var $username = NULL;
	
	var $password = NULL;
	
	var $client = 'smtp_helo_hostname';
	
	var $sender = 'doTen Info <donotreply@doten.co>';
	
	function initialize(&$controller, $settings = array())
	{
	  $this -> host = SMTP_SERVER;
	  $this -> port = SMTP_PORT;
	  $this -> timeout = SMTP_TIMEOUT;
	  $this -> username = SMTP_USERNAME;
	  $this -> password = SMTP_PASSWORD;
	  $this -> sender = SMTP_SENDER;

	  // saving the controller reference for later use
	  $this -> controller =& $controller;
	}
  
	function _sendMail($mailer_data)
	{
	  //$this->Email->reset();

	  /* SMTP Options */
      $this->Email->smtpOptions = array(
		'request' => array('uri' => array('scheme' => 'https')),
        'port'=> $this -> port,
        'timeout'=> $this -> timeout,
        'host' => $this -> host,
        'username'=> $this -> username,
        'password'=> $this -> password,
        'client' => $this -> client,
      );		
      $address = $mailer_data['Mailer']['username'];
	  $User = substr($mailer_data['Mailer']['username'], 0, strpos($mailer_data['Mailer']['username'], '@'));
      $this->Email->to = "$User <$address>";
      $this->Email->replyTo = $this -> sender;
      $this->Email->from = $this -> sender;
      //Send as 'html', 'text' or 'both' (default is 'text')
      $this->Email->sendAs = 'both'; // because we like to send pretty mail
      //Set view variables as normal
      $this -> controller -> set('User', $User);
      $this -> controller -> set('mailer_data', $mailer_data);
      /* Set delivery method */
      $this->Email->delivery = 'smtp';

      /* Do not pass any args to send() */
      $this->Email->send();
      /* Return SMTP errors. */
	  return $this->Email->smtpError;
	}
	
	function sendConfirmationMail($mailer_data)
	{
	  $this->Email->subject = '欢迎注册 - 已成功创建您的点拾网帐户。您或许对以下信息感兴趣。';
	  $this->Email->template = 'confirmation'; // note no '.ctp'
	  
	  if(empty($mailer_data['Mailer']['username']) || empty($mailer_data['Mailer']['confirmation_token']))
	    return 0;
	  
	  return $this -> _sendMail($mailer_data);
	}

	function sendPasswordResetMail($mailer_data)
	{
	  $this->Email->subject = '密码重置 - 您现在可以重新设置您的点拾密码。';
	  $this->Email->template = 'reset_password'; // note no '.ctp'
	  
	  if(empty($mailer_data['Mailer']['username']) || empty($mailer_data['Mailer']['resetpassword_token']))
	    return 0;
	  
	  return $this -> _sendMail($mailer_data);
	}
	
	function sendTradeNoticeMail($mailer_data)
	{
	  $this->Email->subject = '点拾交易通知 - 您在的订单已经成交。';
	  $this->Email->template = 'trade'; // note no '.ctp'
	  
	  if(empty($mailer_data['Mailer']['username']))
	    return 0;
	  
	  return $this -> _sendMail($mailer_data);
	}
	
	function sendPaymentNoticeMail($mailer_data)
	{
	  $this->Email->subject = '点拾交易通知 - 买家已经确认付款。请您尽快查收并确认收款。';
	  $this->Email->template = 'payment'; // note no '.ctp'
	  
	  if(empty($mailer_data['Mailer']['username']))
	    return 0;
	  
	  return $this -> _sendMail($mailer_data);
	}
}
?>
