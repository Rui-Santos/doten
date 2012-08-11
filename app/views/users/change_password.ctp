<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery.pstrength-min.1.2.js">
</script>

<script type="text/javascript">
$(function() {
$('input.password_input').pstrength();
});
</script>
<?php echo $session->flash(); ?>
<?php echo $form -> create('User', array('action' => 'changePassword'));?>
<?php
	echo "<h5>你的用户名是</h5>";
	echo "<input type=\"text\" class=\"input_form\" id=\"disabled_email\" disabled value=\"$username\">";
	echo "<h5>请输入旧密码</h5>";
    echo $form->input('password', array('type' => 'password','label' => '','class'=>'password_input'));
	echo "<h5>请输入新密码</h5>";
    echo $form->input('new_password', array('type' => 'password','label' => '','class'=>'password_input'));
	echo "<h5>请确认新密码</h5>";
    echo $form->input('new_password_again', array('type' => 'password', 'label' => '', 'class'=>'input_form'));
?>

<?php echo '<div style="text-align:center"><div id="submit_login">';
	  echo $this->Form->button('submit', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>