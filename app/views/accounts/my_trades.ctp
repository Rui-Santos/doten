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
	</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
	  foreach ($account['Order'] as $order):
		$class = null;
		
		if($order['status'] != 4)
		  continue;
		  
		if($order['account_id'] != $account_id && $order['trader_account_id'] != $account_id)
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
          case 4:
        		echo "已完成";
        		break;
        	default:
        	  break;
    		}
		  ?>&nbsp;
		</td>
		<td><?php echo $order['amount']; ?>&nbsp;</td>
		<td><?php echo $order['price']; ?>&nbsp;</td>
		<td><?php //echo $order['trader_account_id']; ?>&nbsp;</td>

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