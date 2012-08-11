<?php
//Configure::write('Config.language', 'chi');
 

class PagesController extends AppController
{
  var $name = 'Pages';
  var $helpers = array('Html', 'Form', 'Session');
  var $components = array('Session');
  
  function beforeRender()
  {
  	$this -> set('location', 'Pages');
	$this -> set('action', $this -> action);
	
    $user_data = $this -> Auth -> user();
	if($user_data)
	  $this -> set('username', $user_data['User']['username']);
	  
	$btc_balance = $this -> Session -> read('btc_balance');
	$this -> set('btc_balance', $btc_balance);
  }
  
  function beforeFilter()
  {
    parent::beforeFilter();
  }

  function display()
  {
  	$this -> render(null, null, '/');
  }

  function home()
  {
    $this -> set('title_for_layout', '点拾 doTen.co');
	$this->layout = 'index';
  }
  
  function about()
  {
    $this -> set('title_for_layout', '关于点拾');
	$this->layout = 'index';
  }
  function howto()
  {
	$this -> set('title_for_layout', '点拾 doTen.co');
	$this->layout = 'index';
  }
  function faq()
  {
	$this -> set('title_for_layout', '常见问题');
	$this->layout = 'index';
  }

}
  ?>