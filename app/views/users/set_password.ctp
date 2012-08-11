<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery.pstrength-min.1.2.js">
</script>

<script type="text/javascript">
$(function() {
$('input.password_input').pstrength();
});
</script>
<?php echo $session->flash(); ?>
<?php echo $form -> create('User', array('action' => 'setPassword'));?>
<?php
	echo "<h5>你的用户名是</h5>";
	echo "<input type=\"text\" class=\"input_form\" id=\"disabled_email\" disabled value=\"$username\">";
	echo "<h5>请输入密码</h5>";
    echo $form->input('password', array('type' => 'password','label' => '','class'=>'password_input'));
	echo "<h5>请确认密码</h5>";
    echo $form->input('password_again', array('type' => 'password', 'label' => '', 'class'=>'input_form'));
    /* add the AGREEMENT checkbox here */
	echo '<h6 style="font-size:14px; color:#4C4B41>';
	
	if(isset($agreement))
	  echo $form->input('agreement', array('label' => '&nbsp;&nbsp;我同意<a href="/tnc"><用户许可协议></a></h6>','type' => 'checkbox'));
?>

<?php echo '<div style="text-align:center;"><div id="submit_login">';
	  echo $this->Form->button('submit', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>