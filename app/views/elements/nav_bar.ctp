<style>

ul#nav_bar li a {
	height: 25px;
	text-align:center;
	float:left;
	font: 15px "Microsoft YaHei","STHeiti","Simhei","Hiragino Sans GB","Lucida Grande",Verdana,Arial;
    color: #E8E8E8;
	display: block;
	margin:0px;
    padding: 5px 20px 0 20px;
    text-decoration: none;
	text-shadow: 0 0 10px #1F1F1F;
}
ul#nav_bar li a:hover{
	color: #FFFFFF;
	background-color:#5D5D5D;
	background:-moz-linear-gradient(bottom,#4D4D4D,#252525);
	background:-webkit-gradient(linear,left bottom,left top,from(#4D4D4D),to(#252525));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4D4D4D', endColorstr='#252525');
}

#topnav-5, #topnav-6{
	float:right;
	border-left:#8D8D8D 2px groove;
}
#topnav-1, #topnav-2,#topnav-3,#topnav-4{
	float:left;
	border-right:#8D8D8D 2px groove;
}

ul#nav_bar {
	list-style-type:none;
	text-align:left;
	position:absolute;
	top:0;
	left:0;
	margin:0;
	border-bottom:#6D6D6D 1px solid;
	background-color:#5D5D5D;
	background:-moz-linear-gradient(bottom,#4D4D4D,#6D6D6D);
	background:-webkit-gradient(linear,left bottom,left top,from(#4D4D4D),to(#6D6D6D));
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4D4D4D', endColorstr='#6D6D6D');
	height:30px;
	width:100%;}
	
* html li {
margin:0;
overflow: hidden;
white-space: nowrap;
display: inline;
float: left;}
</style>

<ul id="nav_bar" style="position:fixed;z-index:99999;">
<?php  
if(!isset($username))
$username = '';
if($username)  
{
	$link = '/users/logout' ;
	$title = '登出';}
  else  
{
	$link = '/users/login';
	$title = '登入';
}
  ?>
<li id="topnav-1"><a title="What am I looking for?" href="/">首页</a></li>
<li id="topnav-2"><a href="/accounts" title="我的帐户">账户</a></li>
<li id="topnav-3"><a href="/orders/add" title="提交订单">下单</a></li>
<li id="topnav-4"><a href="/orders" title="交易市场">市场</a></li>
<li id="topnav-4"><a href="/orders/finished" title="交易历史">最近交易</a></li>
<li id="topnav-5"><a href="<?php echo "$link";?>" title="<?php echo "$title";?>" ><?php echo "$title";?></a></li>

<?php
if(!$username)
{ echo '<li id="topnav-6"><a href="/users/register/"title="注册">注册</a></li>';}
else
{
	echo '<li id="topnav-6"><a title="Username">'."$username".'</a></li>';
	echo '<li id="topnav-6"><a title="Username">'."$btc_balance".'&nbsp;BTC</a></li>';}
?>

 
</ul>
