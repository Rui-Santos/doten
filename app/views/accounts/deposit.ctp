<?php echo $form -> create('Accounts', array('action' => 'deposit'));?>
<div>
<?php
	echo $session -> flash(); 
	echo "<h5>请发送比特币到这个只属于你个人的Bitcoin地址。</h5>";?>
	<input type="text" class="withdraw_form" id="btc_address" readOnly value="<?php echo $bind_address; ?>">
	<div class="black_block" id="deposit_direction"  style="margin-top:20px;">
			<ul style="padding:5px 20px 5px 20px;">
			<li>
				<h4 style="color:#E8E8E8; margin-left:-15px;margin-top:-8px;margin-bottom:10px;">比特币充值说明</h4><h5 style="color:#E8E8E8;">1.我们使用上述地址在充值过程中验证您的身份.</h5></li>
			<li><h5  style="color:#E8E8E8;">2.请复制上述地址，并将您想要充值的金额使用实时更新的<a href="http://www.bitcoin.org">客户端程序</a>（或者<a href="http://mybitcoin.com">在线客户端MyBitcoin</a>）发送。</h5></li>
			<li><h5  style="color:#E8E8E8;">3。您的账户余额会在服务器收到充值后更新，请及时查看。</h5></li>
			<li><h5  style="color:#E8E8E8;">4.但请注意的是，在您的转账交易得到6个block的确认之前，这些金额是被冻结的，并不能挂单出售或者消费使用。</h5></li>
						</ul>
	</div>
	
	
	
</div>