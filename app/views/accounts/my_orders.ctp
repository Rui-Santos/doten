<div class="orders index">
	<h2>
	  <?php
	    $account_id = $this -> Session -> read('account_id');
	    echo $this -> Session -> read('alipay_account');?></h2>
	<h3>发起的交易</h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php //echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('创建于', 'created');?></th>
			<th><?php echo $this->Paginator->sort('更新于', 'modified');?></th>
			<th><?php echo $this->Paginator->sort('类型', 'type');?></th>
			<th><?php echo $this->Paginator->sort('状态', 'status');?></th>
			<th><?php echo $this->Paginator->sort('数额', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('价格', 'price');?></th>
			<th><?php //echo $this->Paginator->sort('trader_account_id');?></th>
			<th class="actions"><?php echo '操作';?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
	  foreach ($account['Order'] as $order):
		$class = null;
		
		if($order['status'] == 4)
		  continue;
		  
		if($order['account_id'] != $account_id)
		  continue;

		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>

		<td>
			<?php //echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td><?php echo $order['created']; ?>&nbsp;</td>
		<td><?php echo $order['modified']; ?>&nbsp;</td>
		<td>
		  <?php
		    /* translate the order type */
		    switch($order['type'])
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
		  ?>&nbsp;
		</td>
		<td>
		  <?php
		    /* translate the order status */
		    switch($order['status'])
				{
      		case 0:
        		echo "进行中";
        		break;
      		case 1:
        		echo "已匹配";
        		break;
     		  case 2:
        		echo "已确认";
        		break;
        	default:
        	  break;
    		}
		  ?>&nbsp;
		</td>
		<td><?php echo $order['amount']; ?>&nbsp;</td>
		<td><?php echo $order['price']; ?>&nbsp;</td>
		<td><?php //echo $order['trader_account_id']; ?>&nbsp;</td>
		<td class="actions">
		  <?php
		    switch($order['status'])
				{
				  case 0:
				    echo $this->Html->link('取消订单', array('controller' => 'Orders', 'action' => 'cancel', $order['id']), null, sprintf('你确定要删除 # %s 订单??', $order['id']));
				    break;
      		case 1:
        		if($order['type'] == 'sell' && $order['status'] == 2)
        		  echo $this->Html->link('收款确认', array('controller' => 'Orders', 'action' => 'payee_confirm', $order['id']));
        		break;
     		  case 2:
     		    if($order['type'] == 'buy'  && $order['status'] == 3)
              echo $this->Html->link('付款确认', array('controller' => 'Orders', 'action' => 'payer_confirm', $order['id']));
        		break;
        	default:
        	  break;
    		}

			?>
		</td>

	</tr>
	<?php endforeach; ?>
	<?php endforeach; ?>
	
	</table>
	
	<h3>参与的交易</h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php //echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('创建于', 'created');?></th>
			<th><?php echo $this->Paginator->sort('更新于', 'modified');?></th>
			<th><?php echo $this->Paginator->sort('类型', 'type');?></th>
			<th><?php echo $this->Paginator->sort('状态', 'status');?></th>
			<th><?php echo $this->Paginator->sort('数额', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('价格', 'price');?></th>
			<th><?php //echo $this->Paginator->sort('trader_account_id');?></th>
			<th class="actions"><?php echo '操作';?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
	  foreach ($account['Order'] as $order):
		$class = null;
		
		if($order['status'] == 4)
		  continue;
		  
		if($order['trader_account_id'] != $account_id)
		  continue;
		  
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php //echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td><?php echo $order['created']; ?>&nbsp;</td>
		<td><?php echo $order['modified']; ?>&nbsp;</td>
		<td>
		  <?php
		    /* translate the order type */
		    switch($order['type'])
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
		  ?>&nbsp;
		</td>
		<td>
			<?php
		    /* translate the order status */
		    switch($order['status'])
				{
      		case 0:
        		echo "进行中";
        		break;
      		case 1:
        		echo "已匹配";
        		break;
     		  case 2:
        		echo "已确认";
        		break;
    		}
		  ?>&nbsp;
		</td>
		<td><?php echo $order['amount']; ?>&nbsp;</td>
		<td><?php echo $order['price']; ?>&nbsp;</td>
		<td><?php //echo $order['trader_account_id']; ?>&nbsp;</td>
		<td class="actions">
		  <?php
		    switch($order['status'])
				{
				  case 0:
				    echo $this->Html->link('取消订单', array('controller' => 'Orders', 'action' => 'cancel', $order['id']), null, sprintf('你确定要删除 # %s 订单??', $order['id']));
				    break;
      		case 1:
        		if($order['type'] == 'sell')
       		  {
        		  echo $this->Html->link('付款确认', array('controller' => 'Orders', 'action' => 'payer_confirm', $order['id']));
        		  echo $this->Html->link('取消订单', array('controller' => 'Orders', 'action' => 'cancel', $order['id']), null, sprintf('你确定要取消 # %s 订单??', $order['id']));
       		  }
        		break;
     		  case 2:
     		    if($order['type'] == 'buy')
        		  echo $this->Html->link('收款确认', array('controller' => 'Orders', 'action' => 'payee_confirm', $order['id']));
        		break;
        	default:
        	  break;
    		}

			?>
	</td>
	</tr>
	
	<?php endforeach; ?>
	<?php endforeach; ?>
	
	</table>
	

	<div class="paging">

	</div>
</div>
<div class="actions">
	<h3><?php echo '或许你可以...'; ?></h3>
	<ul>
		<li><?php echo $this->Html->link('新订单', array('controller' => 'Orders', 'action' => 'add')); ?></li>
		<li><?php echo $this->Html->link('我的帐户', array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link('退出', array('controller' => 'Users', 'action' => 'logout')); ?></li>
	</ul>
</div>