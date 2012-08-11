<?php echo $session->flash(); ?>
<?php echo $form -> create('User', array('action' => 'forgotPassword'));?>

<?php
	echo '<h4 id=\'dark_font\' style=\'text-align:center; padding-left:10px;\'>请输入您注册时使用的邮箱.</h4><h5 id=\'dark_font\' style=\'text-align:center;\'>一封重置密码链接的邮件会发到您的注册邮箱.</h6>';
    echo $form->input('username', array('label' => '','class'=>'input_form'));
    /* add the verify code here */
?>

<?php echo '<div style="text-align:center"><div id="submit_login">';
	  echo $this->Form->button('submit', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>