<?php echo $form -> create('Accounts', array('action' => 'withdraw'));?>

<?php
	echo $session -> flash(); 
	echo '<h5 id=\'dark_font\' style="text-align:center;padding-left:20px;margin-bottom:5px;">请输入您的比特币地址</h5>';
    echo $form->input('address', array('label' => '', 'class'=>'withdraw_form', 'id' => 'btc_address'));
	echo '<h5 id=\'dark_font\' style="text-align:center;padding-left:30px;margin-top:10px;">请输入要提取比特币的数量</h5><div style="text-align:center;">';	
    echo $form->input('amount', array('label' => '', 'class'=>'withdraw_form','id'=>'amount'));?>
	<p style="text-align:center;font-size:12px;color:#8E8C8C;font-style:italic;padding-bottom:5px;">每笔提现bitcoin系统自动扣除固定0.0005BTC的手续费</p></div>
<?php echo '</div><div style="text-align:center"><div id="submit_withdraw">';
	  echo $this->Form->button('submit', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	  echo '</div></div>'?>
