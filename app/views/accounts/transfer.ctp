<?php echo $session -> flash(); ?>
<?php echo $form -> create('Account', array('action' => 'transfer'));?>

<?php
	//$alipay_account 
	echo '<h5 id=\'dark_font\' style="text-align:center;padding-left:20px;margin-bottom:5px;">请输入收款人的支付宝邮箱</h5>';
    echo $form->input('dest', array('label' => '', 'class'=>'withdraw_form'));
	echo '<h5 id=\'dark_font\'style="text-align:center;padding-left:30px;margin-bottom:10px;" >请输入您要转账的比特币数额</h5><div style="text-align:center;">';	
	echo $form->input('amount', array('label' => '', 'class'=>'withdraw_form','id'=>'amount'));	
?></div>
<?php echo '</div><div style="text-align:center"><div id="submit_login">';
	  echo $this->Form->button('Send Money!', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>
