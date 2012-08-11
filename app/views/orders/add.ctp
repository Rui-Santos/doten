<?php echo $session -> flash(); ?>
<div class="add_order">
<?php echo $this->Form->create('Order');?>
	<h5 id='dark_font' style="margin:0 5px 0 5px;"><div id="add_order">
	<table id="two_column">
	<tr><td class="order_text">类型</td><td class="order_select"><?php $options = array('buy'=>'买比特币','sell'=>'卖比特币'); echo $this->Form->select('type', $options,null,array('empty' => false));?></td></tr>
	<tr><td class="order_text">数量</td><td class="order_input"><?php echo $this->Form->input('amount', array('label' => '','id'=>'amount','class'=> 'order_form', 'onKeyUp' => 'calc()'));?><td style ="font-size:12px;" class="unit_text">BTC</td></tr>
	<tr><td style="height:30%;text-align:center;font-size:12px;color:#8E8C8C;">测试期手续费</td><td id="fee" style="text-align:left;font-size:12px;color:#8E8C8C;font-style:italic;"><?php $txn_fee = 0.0000; echo "$txn_fee";?></td></tr>
	<tr><td class="order_text">报价</td><td class="order_input"><?php echo $this->Form->input('price', array('label' => '','class'=> 'order_form', 'onKeyUp' => 'calc()'));?></td><td class="unit_text" style ="font-size:12px;">RMB/BTC</td></tr>
	<tr><td class="order_text">总额</td><td class="order_input"><input type="text" class="total_amount" id="total_amount" disabled value="0"></td><td class="unit_text" style ="font-size:12px;">RMB</td></tr>
	</table></h5>

<?php
	echo '</div><div style="text-align:center"><div id="back_button">';
	echo $this->Form->button('Place Order!', array('type'=>'submit','class'=>'button','id'=>'login_button'));
	echo '</div></div>';
?>
<script>
function calc()
{
	var fee = <?php echo $txn_fee;?>;
	//document.getElementById('amount').value = document.getElementById('rawamount').value * (1-fee);
  document.getElementById('total_amount').value = document.getElementById('amount').value * document.getElementById('OrderPrice').value;
  }
</script>
