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
	您在点拾上申请了找回密码，请点击以下链接重设。</td></tr><tr><td style="padding:5px 30px 5px 30px;text-align:left;color:red;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	如果您本人并没有申请重设密码，请不要点击链接。</td></tr><tr><td style="padding:5px 30px 5px 30px;text-align:left;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	<a href='http://doten.co/users/reset/<?php echo $mailer_data['Mailer']['resetpassword_token']; ?>'>确认链接</a></td></tr><tr><td style="padding:5px 30px 5px 30px;text-align:left;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	如果您的邮箱不支持超链接，请将以下地址复制到浏览器地址栏中访问。</td></tr>
	<tr><td style="font-size:12px;font-family:'Lucida Grande';padding:5px 30px 5px 30px;">http://doten.co/users/reset/<?php echo $mailer_data['Mailer']['resetpassword_token']; ?></td></tr>
	<tr><td style="text-align:left;padding:2px 30px 5px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
    如果有其他问题，请联系我们：</br><a href="mailto:cecil@oldtim.es">cecil@oldtim.es</a></td></tr><tr><td style="padding:5px 30px 5px 30px;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
    谢谢！</td></tr><tr><td>
	<tr><td style="text-align:right;font-family:Microsoft yahei,'hiragino sans gb','lucida grande',verdana,arial;color:#222222;">
	点拾网管理组</td></tr>
</table>