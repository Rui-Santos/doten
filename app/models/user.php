<?php

class User extends AppModel
{
  var $name = 'User';
  
  var $hasOne = array('Account' => array(
                           'className' => 'Account',
                           'conditions' => '',
												   'order' => '',
                           'foreignKey' => 'user_id')
                     );

  var $validate = array (
    'username' => array (
      'rule' => 'email',
      'message' => '嘿..我看这玩意可不像电子邮箱.'
    ),
    'alipay_account' => array (
	  'rule' => '/^((\d+){11})|(\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)$/',
      'message' => '请在这里填入支付宝电子邮箱或者手机帐号.'
	  )
  );
  
  function beforeValidate() 
  {
    if(!$this->id) 
    {
      if($this->findByUsername($this->data['User']['username'])) 
      {
        $this->invalidate('username_unique');
        return false;
      }
    }
    return true;
  }
}

?>