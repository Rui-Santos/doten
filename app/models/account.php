<?php

class Account extends AppModel
{
  var $name = 'Account';
  
  var $hasMany = array('Order' => array(
                           'className' => 'Order',
                           'conditions' => '',
							'order' => '',
                           'foreignKey' => 'account_id')
                     );

	var $belongsTo = array('User' => array(
                           'className' => 'User',
                           'conditions' => '',
							'order' => '',
                           'foreignKey' => 'user_id')
                        );
  
    var $validate = array (
    'alipay_account' => array (
	  'rule' => '/^((\d+){11})|(\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)$/',
      'message' => '请在这里填入支付宝电子邮箱或者手机帐号.'
	  ));
  /* called before save */
  /* check and update all orders under the account */
  function beforeSave()
  {
  /*
  	if(empty($this -> data['Account']['btc_balance']))
  	  $this -> data['Account']['btc_balance'] = 0;
  	  
  	if(empty($this -> data['Account']['btc_frozen']))
  	  $this -> data['Account']['btc_frozen'] = 0;
  */
	
	/* tx fee, need to get it into the config */
  	if($this -> data['Account']['btc_balance'] < 0 || $this -> data['Account']['btc_frozen'] < 0)
  	  return 0;

  	return 1;
  }

}

?>