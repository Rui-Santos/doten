<table cellpadding="6" width="480" style="background: none repeat scroll 0 0 #f2f2f2;
    border: 1px solid #e5e5e5;
	border-radius: 11px;
	-moz-box-shadow: 0px 0px 20px #bbbbbb;
	-webkit-box-shadow: 0x 0px 20px #bbbbbb;
	box-shadow: 0px 0px 20px #bbbbbb;
	width:480px;
	margin:30px 35px 30px 35px;
	padding:10px;
	word-break:break-all;
	text-align:left;
	font:14px 'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;	">
	<tr><td style="font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	尊敬的<?php echo $User; ?> ：</td></tr>
	<tr><td style="font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">您好！</td></tr>
	<tr><td style="padding:5px 30px 5px 30px;text-align:left;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	您在点拾上的一个订单已于 <?php echo $mailer_data['Mailer']['conf_time'];?> 成交。</td></tr>
	<tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	订单号:<?php echo $mailer_data['Mailer']['order_id'];?></td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	成交价格:<?php echo $mailer_data['Mailer']['price'];?> RMB</td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	数量:<?php echo $mailer_data['Mailer']['amount'];?> BTC</td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	总额:<?php echo $mailer_data['Mailer']['total'];?> RMB</td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	买家:<?php echo $mailer_data['Mailer']['buyer_alipay_account'];?></td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	卖家:<?php echo $mailer_data['Mailer']['seller_alipay_account'];?></td></tr><tr><td style="padding:0px 35px 0px 60px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	请点击此链接到点拾查看并确认：<a href='http://www.doTen.co/orders/view/<?php echo $mailer_data['Mailer']['order_id']; ?>'>交易详情</a></td></tr>
	<tr><td style="padding:5px 30px 5px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	如果您的邮箱不支持超链接，请将以下地址复制到浏览器地址栏中访问。</td></tr>
	<tr><td style="font-size:12px;font-family:'Lucida Grande';padding:5px 30px 5px 30px;">http://www.doTen.co/orders/view/<?php echo $mailer_data['Mailer']['order_id']; ?></td></tr>
	<tr><td style="text-align:left;padding:2px 30px 5px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
    如果有其他问题，请联系我们：</td></tr><tr><td style="padding:0px 30px 0px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	<a href="mailto:cecil@oldtim.es">cecil@oldtim.es</a></td></tr><tr><td style="padding:0px 30px 0px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
    谢谢！</td></tr><tr><td style="padding:5px 30px 5px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	<tr><td style="text-align:right;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	点拾网管理组</td></tr>
</table>