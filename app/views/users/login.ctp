<?php
  echo $session->flash();
  echo $session->flash('auth');
?>

<?php echo $form->create('User', array('action' => 'login')); ?>

<?php
	echo "<h5>邮箱</h5>";
    echo $form->input('username', array('label' => '','class'=>'input_form')); 
	echo "<h5>密码</h5>";
	echo $form->input('password', array('label' => '','class'=>'input_form'));
	echo "<h6>";
	echo $form->input('rememberme', array('label' => '记住我</h6>','type' => 'checkbox'));
?>

<?php echo '<div style="text-align:center"><div id="submit_login">';
	  echo $this->Form->button('Log In', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>

<?php
	echo '<div style="text-align:center; margin-left:40px;"><h5 id="register_link">';
	echo $html->link('注 册', array('action' => 'register'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo $html->link('忘记密码了?', array('action' => 'forgotPassword'));
	echo '</h5></div>';?>