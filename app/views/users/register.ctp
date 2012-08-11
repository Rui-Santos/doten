<?php echo $session -> flash(); ?>
<?php echo $form -> create('User', array('action' => 'register'));?>
<?php
	echo '<h5 id=\'dark_font\'>请使用您常用的邮箱注册</h5>';
    echo $form->input('username', array('label' => '', 'class'=>'input_form','id'=>'username','onKeyUp' => 'copy()','after' => $form->error('username_unique', '呃..这个邮箱不知道被哪个家伙占了.')));
	echo '<h5 id=\'dark_font\'>请输入您的支付宝账户(邮箱或手机号)</h5><h6 style=\'color:red;\'>(该账户注册后不可更改)</h6>';	
    echo $form->input('alipay_account', array('label' => '', 'class'=>'input_form','id'=>'alipay','after' => $form->error('alipay_unique', '呃..这个支付宝用过了啦！')));
?>
<?php
	echo '</div><div style="text-align:center"><div id="submit_login">';
	echo $this->Form->button('Sign Up!', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	echo '</div></div>'
?>
<script>
function copy()
{
  document.getElementById('alipay').value = document.getElementById('username').value;
}
</script>