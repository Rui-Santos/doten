<div class="accounts">
	<div class="two_column" id="left_column">
			<h3 id="actions_title" >你可以...</h3>
			<div class="actions">
				<a class="button" id="button_add_order" href="/orders/add"><h5 class="link">下 单</h5></a>
			</div>
			<div class="actions">
				<a class="button" id="button_deposit" href="/accounts/deposit"><h5 class="link">充 值</h5></a>
			</div>
			<div class="actions">
				<a class="button" id="button_withdraw" href="/accounts/withdraw"><h5 class="link">提 现</h5></a>
			</div>
			<div class="actions">
				<a class="button" id="button_send" href="/accounts/transfer"><h5 class="link">转 账</h5></a>	
			</div>
			<div class="actions">
				<a class="button" id="button_setting" href="/accounts/mySetting"><h5 class="link">修改密码</h5></a>
			</div>
	</div>
	<div class="two_column" id="right_column">
	<?php echo $session -> flash(); ?>
			<div><h3 id="title_1">帐户余额</h3></div>
			<div class="black_block" id="block_1"  style="margin-bottom:10px;">
			<ul>
			<li class="IE_sucks"><?php echo '<p>可用余额&nbsp;&nbsp;&nbsp;&nbsp;:</p><p class="amount"> ' . $account_balance['confirmed'] . '</p>
			<p style="width:5px;margin-left:30%;">&nbsp;BTC<br></p>';?></li>
			<li class="IE_sucks"><?php echo '<p>交易进行中&nbsp;:</p><p class="amount"> ' . $account_balance['btc_frozen'] . '</p><p style="width:5px;margin-left:30%;">&nbsp;BTC<br>';?></li>
			<li class="IE_sucks"><?php echo '<p>充值确认中&nbsp;:</p><p class="amount"> ' . $account_balance['unconfirmed'] . '</p><p style="width:5px;margin-left:30%;">&nbsp;BTC<br>';?>	
			</li>
			<li class="IE_sucks"><?php echo '<p>绑定支付宝:</p><p class="alipay_address"> ' . $account_alipay . '</p>';?>	
			</li></ul>
			</div>
			<div><h3 id="title_2">进行中交易</h3></div>
			<div class="black_block" id="block_2"  style="margin-bottom:10px;">
			<table class="orders">
			<tr style="border:0px;">
			<th style="width:8%;">单号 <!-- echo $this->Paginator->sort('交易单号','id');--></th>
			<th style="width:10%;">类型<!--  echo $this->Paginator->sort('类型', 'type');--></th>
			<th style="width:10%;">报价<!--  echo $this->Paginator->sort('报价', 'price');--></th>
			<th style="width:10%;">数量<!--  echo $this->Paginator->sort('数量', 'amount');--></th>
			<th style="width:15%;">总额<!--  echo $this->Paginator->sort('总额', 'total');--></th>
			<th style="width:18%;">状态<!--  echo $this->Paginator->sort('状态', 'status');--></th>
			<th style="width:12%;">操作</th>
			</tr>
			<?php
			  $i = 0;
			  foreach ($orders as $order):
			    $class = null; ?>
			<tr> <!--<?php echo $class; ?>-->

			
			<td><?php echo $order['Order']['id']; ?></td>
			<td>
			<?php
			  	/* translate the order type */
		      	switch($order['Order']['type'])
				{
      		  	case 'sell':
        		  	echo "卖单";
        		  	break;
      		  	case 'buy':
        		  	echo "买单";
        		  	break;
        	  	default:
        	    	break;
    		  	}
			?>
			</td>
			<td><?php echo $order['Order']['price']; ?></td>
			<td><?php echo $order['Order']['amount']; ?></td>
			<td><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>
			<td>
			<?php
			  	/* translate the order status */
		      	switch($order['Order']['status'])
				{
      		  	case 0:
        		  	echo "进行中";
        		  	break;
      		  	case 1:
        		  	echo "已匹配";
        		  	break;
     		    case 2:
        		  	echo "已付款";
        		  	break;
        	  	default:
        	    	break;
    		  	}
			?>
			</td>
			<td class="order_actions">
			<?php	if($order['Order']['status']!=0) echo $this->Html->link('查看交易', array('controller' => 'Orders', 'action' => 'view', $order['Order']['id']),array('class' => 'action_button'));
				switch($order['Order']['status'])
				{
				  case 0:
				    if($order['Order']['account_id'] == $account_id)
				      echo $this->Html->link('取消订单', array('controller' => 'Orders', 'action' => 'cancel', $order['Order']['id']),array('class' => 'action_button'), sprintf('你确定要取消 # %s 订单?', $order['Order']['id']));
				    break;
      			  case 1:
        			if(($order['Order']['type'] == 'sell' && $order['Order']['trader_account_id'] == $account_id) || ($order['Order']['type'] == 'buy' && $order['Order']['account_id'] == $account_id))
        		  	  echo $this->Html->link('确认付款', array('controller' => 'Orders', 'action' => 'payer_confirm', $order['Order']['id']),array('class' => 'action_button'), sprintf('你已经付款给买家了吗?', $order['Order']['id']));
					if(($order['Order']['type'] == 'sell' && $order['Order']['trader_account_id'] == $account_id) || ($order['Order']['type'] == 'buy' && $order['Order']['account_id'] == $account_id))
				      echo $this->Html->link('取消交易', array('controller' => 'Orders', 'action' => 'cancel', $order['Order']['id']),array('class' => 'action_button'), sprintf('你确定要取消 # %s 订单?', $order['Order']['id']));
        			break;
     		 	  case 2:
     		    	if(($order['Order']['type'] == 'sell' && $order['Order']['account_id'] == $account_id) || ($order['Order']['type'] == 'buy' && $order['Order']['trader_account_id'] == $account_id))
              		  echo $this->Html->link('确认收款', array('controller' => 'Orders', 'action' => 'payee_confirm', $order['Order']['id']),array('class' => 'action_button'), sprintf('你已经收到对应 # %s 订单的货款了吗?', $order['Order']['id']));
        			break;
        		  default:
        	  		break;
    			}
			?>
			</td></tr>
			<?php endforeach; ?>
			</table>
		</div>
	<div><h3 id="title_2">已结束交易</h3></div>
		<div class="black_block" id="block_3"  style="margin-bottom:10px;">
			<table class="orders">
			<tr style="border:0px;">
			<th style="width:8%;">单号 <!-- echo $this->Paginator->sort('交易单号','id');--></th>
			<th style="width:10%;">类型<!--  echo $this->Paginator->sort('类型', 'type');--></th>
			<th style="width:10%;">报价<!--  echo $this->Paginator->sort('报价', 'price');--></th>
			<th style="width:10%;">数量<!--  echo $this->Paginator->sort('数量', 'amount');--></th>
			<th style="width:15%;">总额<!--  echo $this->Paginator->sort('总额', 'total');--></th>
			<th style="width:18%;">状态<!--  echo $this->Paginator->sort('状态', 'status');--></th>
			<th style="width:12%;">操作</th>
			</tr>
			<?php
			  $i = 0;
			  foreach ($orders_finished as $order):
			    $class = null;
		
			    if ($i++ % 2 == 0) {
			      $class = ' class="orders"';
			} ?>
			<tr> <!--<?php echo $class; ?>-->

			
			<td><?php echo $order['Order']['id']; ?></td>
			<td>
			<?php
			  	/* translate the order type */
		      	switch($order['Order']['type'])
				{
      		  	case 'sell':
        		  	echo "卖单";
        		  	break;
      		  	case 'buy':
        		  	echo "买单";
        		  	break;
        	  	default:
        	    	break;
    		  	}
			?>
			</td>
			<td><?php echo $order['Order']['price']; ?></td>
			<td><?php echo $order['Order']['amount']; ?></td>
			<td><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>
			<td>
			<?php 	echo "已结束";?>
			</td>
			<td class="order_actions">
			<?php echo $this->Html->link('查看交易', array('controller' => 'Orders', 'action' => 'view', $order['Order']['id']),array('class' => 'action_button'));?>
			</td></tr>
			<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>