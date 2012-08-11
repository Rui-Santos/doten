<?php
//Configure::write('Config.language', 'chi');
 

class UsersController extends AppController
{
  var $name = 'Users';
  var $helpers = array('Html', 'Form', 'Session');
  var $components = array('RequestHandler', 'Mailer', 'Session', 'Cookie');

  function beforeRender()
  {
  	$this -> set('location', 'Users');
		
	$user = $this -> Auth -> user();
	if($user)
	  $this -> set('username', $user['User']['username']);
	
	$btc_balance = $this -> Session -> read('btc_balance');
	$this -> set('btc_balance', $btc_balance);
  }
  
  function beforeFilter()
  {
    parent::beforeFilter();
  }
  
  function register()
  {
	$this -> set('title_for_layout', '注册');
    $this->layout = 'login';
	
	if($this -> Auth -> user())
	  $this -> redirect('/');
	  
    if ($this -> data)
    {
      /* generate the confirmation_token */
      $this -> data['User']['confirmation_token'] = md5($this -> data['User']['username'] . rand());
      $this -> data['User']['password'] = md5($this -> data['User']['username'] . rand());
      /* lock the user */
      $this -> data['User']['is_locked'] = 1;
      /* get the alipay_account from user input */
      $this -> data['Account']['alipay_account'] = $this -> data['User']['alipay_account'];
	  
	  
      if($this -> User -> create() && $this -> User -> saveAll($this -> data, array('validate' => 'first')))
      {
        $this -> Session -> setFlash(__d('messages', 'USERS_REGISTER_SUCCEED', TRUE));
        
		$this -> data['User']['account_id'] = $this -> User -> Account -> id;
		$this -> User -> save($this -> data);
		
        /* email the confirmation token here */
        $this -> Mailer -> sendConfirmationMail(array('Mailer' => array(
														'username' => $this -> data['User']['username'],
														'confirmation_token' => $this -> data['User']['confirmation_token'])));

        /* it is clever to use redirect here */
        //$this -> redirect(array('action' => 'index'), null, true);
      }
	  else
	    $this -> Session -> setFlash(__d('errors', 'USERS_REGISTER_FAILED', TRUE));
    }
  }
  
  function login()
  {
    $this -> layout = 'login';
	$this -> set('title_for_layout', '登入');
	
	if($this -> data)
	{
	  if($this -> Auth -> login($this -> data))
	  {
	    $user = $this -> Auth -> user();
		$this -> User -> read(null, $user['User']['id']);
		$this -> User -> set('last_ip', $this -> RequestHandler -> getClientIp());
		$this -> User -> save();
		
	    if($this -> data['User']['rememberme'])
        {
          $cookie = array();
          $cookie['username'] = $this->data['User']['username'];
          $cookie['password'] = $this->data['User']['password'];
          $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
          unset($this->data['User']['rememberme']);
        }
		$this -> Session -> delete('Message.auth');
        $this -> redirect($this -> Auth -> redirect());
	  }
	  else
	  {
	    $user = $this -> User -> findByUsername($this -> data['User']['username']);
		if($user)
		{
		  $this -> User -> read(null, $user['User']['id']);
		  $this -> User -> set('fail_ip', $this -> RequestHandler -> getClientIp());
		  $this -> User -> save();
		}
	  }
	}

    if(empty($this->data))
    {
      $cookie = $this -> Cookie -> read('Auth.User');
      if(!is_null($cookie))
	  {
        if($this -> Auth -> login($cookie))
        {
		  	    $user = $this -> Auth -> user();
		$this -> User -> read(null, $user['User']['id']);
		  $this -> User -> set('last_ip', $this -> RequestHandler -> getClientIp());
		  $this -> User -> save();
          //  Clear auth message, just in case we use it.
          $this -> Session -> delete('Message.auth');
          $this -> redirect($this -> Auth -> redirect());
        }
        else
        {
          // Delete invalid Cookie
          $this->Cookie->delete('Auth.User');
        }
      }
    }
  }

  function logout()
  {
    $this -> set('title_for_layout', '登出');
	
	$this->Cookie->delete('Auth.User');
	$this -> redirect($this -> Auth -> logout());
  }

  function confirm($confirmation_token = NULL)
  {
    $this->layout = 'login';
	
	if($this -> Auth -> user())
	  $this -> redirect('/');
    
    /* strlen = 32 : reg confirm */
    if(empty($confirmation_token) || strlen($confirmation_token) != 32)
      $this -> redirect('/');
		
    $user_data = $this -> User -> findByConfirmationToken($confirmation_token);
        
    if(empty($user_data))
      $this -> redirect('/');
    else
    {
	  /* user not locked */
	  if($user_data['User']['is_locked'] != 1)
	    $this -> redirect('/');

      $this -> Session -> write('confirm', 1);
      $this -> Session -> write('user_id', $user_data['User']['id']);
      $this -> Session -> write('username', $user_data['User']['username']);
      $this -> redirect(array('action' => 'setPassword'));
    }
  }
  
  function reset($resetpassowrd_token = NULL)
  {
    $this->layout = 'login';
	
	if($this -> Auth -> user())
	  $this -> redirect('/');
    
    /* strlen = 32 : reg confirm */
    if(empty($resetpassowrd_token) || strlen($resetpassowrd_token) != 32)
      $this -> redirect('/');
		
    $user_data = $this -> User -> findByResetpasswordToken($resetpassowrd_token);
        
    if(empty($user_data))
      $this -> redirect('/');
    else
    {
      $this -> Session -> write('confirm', 2);
      $this -> Session -> write('user_id', $user_data['User']['id']);
      $this -> Session -> write('username', $user_data['User']['username']);
      $this -> redirect(array('action' => 'setPassword'));
    }
  }
	
	function setPassword()
	{
		$this -> layout = 'login';
		$this -> set('title_for_layout', '设定密码');
		$confirm = $this -> Session -> read('confirm');
		$user_id = $this -> Session -> read('user_id');
		$username = $this -> Session -> read('username');

		/* confirm = 1 : reg confirm ; 2 : forgot password */
		if(!$confirm || !$user_id)
		  $this -> redirect('/');
		  
		if($confirm == 1)
		  $this -> set('agreement', 1);
		
		$this -> set('username', $username);
		
		if($this -> data)
        {
    	  /* the AGREEMENT check */
    	  if(!$this -> data['User']['agreement'] && $confirm == 1)
		  {
    	    $this -> Session -> setFlash(__d('messages', 'USERS_CONFIRM_AGREEMENT', TRUE));
			/* redirect to myself */
			$this -> redirect($this -> referer());
		  }
		  
		  if($this -> data['User']['password'] != $this -> data['User']['password_again'])
		  {
		    $this -> Session -> setFlash(__d('errors', 'USERS_SETPASSWORD_DIFF', TRUE));
			/* redirect to myself, no need */
			//$this -> redirect($this -> referer());
		  }
    	  
    	  $result = $this -> User -> findById($user_id);
          if(empty($result))
		    $this -> redirect('/');
		  if($confirm == 1)
		  {
            /* not confirmed, go away */
            if($result['User']['is_locked'] != 1)
			{
			  $this -> Session -> setFlash(__d('errors', 'USERS_SETPASSWORD_NOTREG', TRUE));
              return 0;
			}
            
            $result['User']['password'] = $this -> Auth -> password($this -> data['User']['password']);
            $result['User']['is_locked'] = 0;
            $result['User']['confirmed_at'] = date("Y-m-d H:i:s");
            $this -> User -> save($result);
            /* go to login */
			$this -> Session -> setFlash(__d('messages', 'USERS_SETPASSWORD_SUCCEED', TRUE));
            $this -> redirect($this->Auth->loginAction);
          }
		  
		  if($confirm == 2)
		  {
		    /* locked, go away */
            if($result['User']['is_locked'] == 1)
			{
			  $this -> Session -> setFlash(__d('errors', 'USERS_SETPASSWORD_LOCKED', TRUE));
              return 0;
			}
            
            $result['User']['password'] = $this -> Auth -> password($this -> data['User']['password']);
            $result['User']['reseted_at'] = date("Y-m-d H:i:s");
			$result['User']['resetpassword_token'] = '';
            $this -> User -> save($result);
            /* go to login */
			$this -> Session -> setFlash(__d('messages', 'USERS_SETPASSWORD_SUCCEED', TRUE));
            $this -> redirect($this -> Auth -> loginAction);
		  }
		}
	}
	
	function forgotPassword()
	{
      $this->layout = 'login';
      $this -> set('title_for_layout', '忘记密码');
      /* are you kidding? you logged in! */ 
      if($this -> Auth -> user())
        $this -> redirect('/');
		  
      if($this -> data)
      {
	    $username = $this -> data['User']['username'];
        $result = $this -> User -> findByUsername($username);
		
		/* user not found */
        if(!$result)
          $this -> redirect('/');
		
		/* locked by admin */
		if($result['User']['is_locked'] == 1 && empty($result['User']['resetpassword_token']))
		{
		  $this -> Session -> setFlash(__d('errors', 'USERS_FORGOT_LOCKED', TRUE));
		  return;
		}
		
		/* maybe not confirm yet */
		if($result['User']['is_locked'] == 1)
		{
		  $this -> Session -> setFlash(__d('errors', 'USERS_FORGOT_FAILED', TRUE));
		  return;
		}

        /* generate the confirmation_token */
        $result['User']['resetpassword_token'] = md5($result['User']['confirmation_token'] . rand());
        /* set locked time */
        date_default_timezone_set("PRC");
        $result['User']['reseted_at'] = date("Y-m-d H:i:s");

        if($this -> User -> save($result))
        {
      	  /* send the email */
      	 $ii = $this -> Mailer -> sendPasswordResetMail(array('Mailer' => array(
														'username' => $result['User']['username'],
														'resetpassword_token' => $result['User']['resetpassword_token'])));


		  $this -> Session -> setFlash(__d('messages', 'USERS_CONFIRMATION_SENT', TRUE) . $ii);
        }
      }
	}
	
	function changePassword()
	{
	  $this -> layout = 'login_nav';
	  $this -> set('title_for_layout', '更改密码');
	  if($this -> data)
	  {
	    $cur_user = $this -> Auth -> user();
	    $user_data = $this -> User -> findById($cur_user['User']['id']);
		
		if($user_data['User']['password'] != $this -> Auth -> password($this -> data['User']['password']))
		{
		    $this -> Session -> setFlash(__d('errors', 'USERS_CHANGE_INCORRECT', TRUE));
			/* redirect to myself */
			$this -> redirect($this -> referer());
		}
	    
		if($this -> data['User']['new_password'] != $this -> data['User']['new_password_again'])
		{
		    $this -> Session -> setFlash(__d('errors', 'USERS_CHANGE_DIFF', TRUE));
			/* redirect to myself */
			$this -> redirect($this -> referer());
		}
		
		$user_data['User']['password'] = $this -> Auth -> password($this -> data['User']['new_password']);
		
		if($this -> User -> save($user_data))
		  $this -> Session -> setFlash(__d('messages', 'USERS_CHANGE_SUCCEED', TRUE));
		else
		  $this -> Session -> setFlash(__d('errors', 'USERS_CHANGE_FAILED', TRUE));
	  }
	}
}
?>
