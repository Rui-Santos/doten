<form  class="contract_overview">
<?php
	echo $session -> flash(); 
?>  <h5 style="text-align:center;color:#4C4B41;padding-left:20px;font-size:18px"><?php if($type == 'buy') echo '卖出'; else echo '买入';?>合同</h5>
	<div class="black_block" id="deposit_direction"  style="margin-top:20px;">
			<ul style="padding:5px 0px 5px 20px;">
			<li>
				<h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-8px;margin-bottom:5px;">成交价</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php echo $price;?><a class="tag">RMB</a></h4></li>
			<li>
				<h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">数量</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php echo $amount;?><a class="tag">BTC</a></h4></li>
			<li><h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">总额</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php echo ($amount * $price);?><a class="tag">RMB</a></h4></li>
			<li><h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;"><?php if($type == 'sell') echo '请使用<span style="color:red;">直接到账方式</span>支付以上总额到卖家支付宝并在我的帐户内确认</br><span style="color:#FF0000;">为保障卖家权益，如果买家未于成交后30分钟内确认付款，该订单将会被系统视为无效并重新放回市场。</span>'; else echo '买家将使用此支付宝地址付款';?></h5><input type="text" class="alipay_address" readOnly value="<?php echo "$alipay_account" ?>"></h5></li>
			
						</ul>
	</div>
</form >
<div style="text-align:center"><div id="submit_login">
<?php 
echo $this->Form->button('返回', array('type'=>'button','onClick'=>"window.location.href='/orders/'",'class'=>'button','id'=>'login_button'));
?></div></div>