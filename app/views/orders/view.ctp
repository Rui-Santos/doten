<form class="trade_overview">
<?php
	echo $session -> flash(); 
?>
<h5 style="text-align:center;color:#4C4B41;padding-left:20px;font-size:18px">交易记录</h5>
	<div class="black_block" id="deposit_direction"  style="margin-top:20px;">
			<ul style="padding:5px 0px 5px 20px;">
			<li>
				<h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-15px;margin-bottom:5px;">成交价</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;"><?php echo $price;?><a class="tag">RMB</a></h4></li>
			<li>
				<h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">数量</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php echo $amount;?><a class="tag">BTC</a></h4></li>
			<li><h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">总额</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php echo ($amount * $price);?><a class="tag">RMB</a></h4></li>
			<li><h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">买家</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php if($type == 'buy') echo "$alipay_account"; else echo "$trader_alipay_account";?></h4></li>
			<li><h5 style="color:#E8E8E8; margin-left:-15px;margin-top:-3px;margin-bottom:5px;">卖家</h5><h4 style="color:#E8E8E8;text-align:center;margin-top:-5px;""><?php if($type == 'buy') echo "$trader_alipay_account"; else echo "$alipay_account";?></h4></li>
			
						</ul>
	</div>
</form>
<div style="text-align:center"><div id="submit_login">
<?php 
echo $this->Form->button('返回', array('type'=>'button','onClick'=>"window.location.href='/accounts/'",'class'=>'button','id'=>'login_button'));
?></div></div>