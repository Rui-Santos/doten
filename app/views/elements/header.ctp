<h1 align="center"><a href="/"><img border= "0" src="/logo.png"></a></h1>
		<div id="header"><div>
		<ul id="nav">
		
		  <?php

       echo $html->link("首页","/");
       echo '    ';
       
       if($location == 'Accounts')
         echo '<b>帐户</b>';
       else
         echo $html->link("帐户","/Accounts");
       echo '    ';
  		 
  		 if($location == 'Orders')
         echo '<b>市场</b>';
       else
         echo $html->link("市场","/Orders");
       echo '    ';
  
       echo $html->link("FAQ","/about");
       echo '    ';
  
       if($this -> Session -> check('account_id'))  
         echo $html->link("退出","/Users/logout");   
       else  
         echo $html->link("登录","/Users/login");
       echo '    ';
    
       ?>
        
		</ul>
		<ul id="nav_2">
		
		 <?php
		 
		   if($location == 'Accounts')
		   {
		     if($action == 'index')
		       echo '<b>我的帐户</b>';
		     else
  		     echo $html->link("我的帐户","/Accounts");
         echo '    ';
          
         if($action == 'myOrders')
		       echo '<b>进行中的交易</b>';
		     else
  		     echo $html->link("进行中的交易","/Accounts/myOrders");
         echo '    ';
  
  			 if($action == 'myTrades')
		       echo '<b>已完成的交易</b>';
		     else
  		     echo $html->link("已完成的交易","/Accounts/myTrades");
         echo '    ';
       }
       
       if($location == 'Orders')
		   {
		     if($action == 'index')
		       echo '<b>总览</b>';
		     else
  		     echo $html->link("总览","/Orders");
         echo '    ';
         
         if($action == 'buy')
		       echo '<b>我要买</b>';
		     else
  		     echo $html->link("我要买","/Orders/buy"); 
         echo '    ';
         
         if($action == 'sell')
		       echo '<b>我要卖</b>';
		     else
  		     echo $html->link("我要卖","/Orders/sell");
         echo '    ';

  			 if($action == 'finished')
		       echo '<b>已完成的交易</b>';
		     else
  		     echo $html->link("已完成的交易","/Orders/finished");
         echo '    ';
       }
       
		  ?>
		  
		</ul>
	  </div></div>

