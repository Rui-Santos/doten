尊敬的<?php echo $User;?>：
您好！
	您在点拾上的一个订单对方已于 <?php echo $mailer_data['Mailer']['conf_time'];?> 确认付款。
		订单号:<?php echo $mailer_data['Mailer']['order_id'];?>
		成交价格:<?php echo $mailer_data['Mailer']['price'];?>RMB
		数量:<?php echo $mailer_data['Mailer']['amount'];?>BTC
		总额:<?php echo $mailer_data['Mailer']['total'];?>RMB
		买家:<?php echo $mailer_data['Mailer']['buyer_alipay_account'];?>
		卖家:<?php echo $mailer_data['Mailer']['seller_alipay_account'];?>
		请点击此链接到点拾查看并确认：
	<?php echo $mailer_data['Mailer']['link'];?>
	
    如果有其他问题，请联系我们：
	cecil@oldtim.es
	
	谢谢
	
	点拾网管理组