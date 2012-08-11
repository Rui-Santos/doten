<div class="orders buy">
	<h2>
	  <?php
	    $account_id = $this -> Session -> read('account_id');
	    echo $this -> Session -> read('alipay_account');?></h2>
	<h3>全部买单</h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php //echo $this->Paginator->sort('account_id');?></th>
			<th><?php echo $this->Paginator->sort('创建于', 'created');?></th>
			<th><?php echo $this->Paginator->sort('类型', 'type');?></th>
			<th><?php echo $this->Paginator->sort('状态', 'status');?></th>
			<th><?php echo $this->Paginator->sort('数额', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('价格', 'price');?></th>
			<th><?php //echo $this->Paginator->sort('trader_account_id');?></th>
			<th class="actions"><?php echo '操作';?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($orders as $order):
		$class = null;
		
		if($order['Order']['type'] != 'buy')
		  continue;
		
		if($order['Order']['status'] != 0 && $order['Order']['status'] != 1)
		  continue;
		  
		if($order['Order']['trader_account_id'] == $account_id || $order['Order']['account_id'] == $account_id)
		  continue;

		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>

		<td>
			<?php //echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td><?php echo $order['Order']['created']; ?>&nbsp;</td>
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
		  ?>&nbsp;
		</td>
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
        	default:
        	  break;
    		}
		  ?>&nbsp;
		</td>
		<td><?php echo $order['Order']['amount']; ?>&nbsp;</td>
		<td><?php echo $order['Order']['price']; ?>&nbsp;</td>
		<td><?php //echo $order['Order']['trader_account_id']; ?>&nbsp;</td>
		
		<td class="actions">
		  <?php
		    switch($order['Order']['status'])
				{
				  case 0:
				    echo $this->Html->link('Match', array('controller' => 'Orders', 'action' => 'match', $order['Order']['id']), null, sprintf('你确定要Match # %s 订单??', $order['Order']['id']));
				    break;
        	default:
        	  break;
    		}

			?>

	</tr>
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