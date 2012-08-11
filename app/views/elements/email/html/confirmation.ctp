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
	尊敬的用户：</td></tr>
	<tr><td style="font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	您好！</td></tr>
	<tr><td style="padding:10px 35px 15px 100px;font-size:16px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">欢迎使用 <a href="http://doTen.co">点拾doTen.co</a></td></tr>
	<tr><td style="padding:5px 30px 5px 30px;ext-align:left;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	全新的第三方中文比特币交易平台。很酷吧？我们也这么想。</td></tr>
	<tr><td style="text-align:left;padding:5px 30px 5px 30px;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	点击以下地址激活您的帐户并设置密码(为了您的账户安全，请尽量避免使用和此邮箱相同的密码)：</td></tr>
	<tr><td style="padding:5px 30px 5px 30px;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	<a href='http://doten.co/users/confirm/<?php echo $mailer_data['Mailer']['confirmation_token']; ?>'>确认链接</a></td></tr>
	<tr><td style="padding:5px 30px 5px 30px;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	如果您的邮箱不支持超链接，请将以下地址复制到浏览器地址栏中访问。</td></tr>
	<tr><td style="font-size:12px;font-family:'lucida grande';padding:5px 30px 5px 30px;">http://doten.co/users/confirm/<?php echo $mailer_data['Mailer']['confirmation_token']; ?>
	<tr><td style="padding:5px 30px 5px 30px;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
    由衷感谢您对本网站的明智选择，如果您需要了解更多点拾网的信息，</br>请参阅:<a href="http://doten.co/faq">常见问题</a></br>
    希望您今后继续支持我们。</br>
	</td></tr>
	<tr><td style="text-align:left;padding:2px 30px 5px 30px;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	我们采取了多重安全措施来保证您帐户内的资金安全，以下是您的个人确认码，请妥善保存本邮件以便于以后使用。</br>
	<span style="font-szie:12px;font-family:'lucida grande'"><?php echo $mailer_data['Mailer']['confirmation_token']; ?></span></td></tr>
	<tr><td style="text-align:right;font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	Cecil Li</td></tr>
	<tr><td style="font-family:'microsoft yahei','hiragino sans gb','lucida grande',verdana,arial;color:#222222;text-align:right;">点拾网管理组</td></tr>
</table>