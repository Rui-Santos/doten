<div class="overview">
	<?php echo $session -> flash(); ?>
	<div class="two_column">
	<div class="orders_index">卖出历史</div>
	<table class="buy_sell">
	<tr>	<th><?php echo $this->Paginator->sort('订单号','order_id');?></th>
			<th><?php echo $this->Paginator->sort('用户 ID','account_id');?></th>
			<th><?php echo $this->Paginator->sort('成交于', 'modified');?></th>
			<th><?php echo $this->Paginator->sort('报价', 'price');?></th>
			<th><?php echo $this->Paginator->sort('数量', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('总额', 'total');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($orders_sell as $order):
		$class = null;
		
	?>
	
	<tr>

		<td style="padding-left:1px;">
			<?php echo $order['Order']['id'];
			//echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td style="font-family:'Lucida Grande';">
			<?php echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['modified']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['price']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['amount']; ?></td>
		<td "><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>
	</tr>
	<?php endforeach; ?>
	</table></div><div style="margin-left:2%;" class="two_column">
	<div class="orders_index">买进历史</div>
	<table class="buy_sell">
	<tr>	<th><?php echo $this->Paginator->sort('订单号','order_id');?></th>
			<th><?php echo $this->Paginator->sort('用户 ID','account_id');?></th>
			<th><?php echo $this->Paginator->sort('成交于', 'modified');?></th>
			<th><?php echo $this->Paginator->sort('报价', 'price');?></th>
			<th><?php echo $this->Paginator->sort('数量', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('总额', 'total');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($orders_buy as $order):
		$class = null;
		
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td style="padding-left:1px;">
			<?php echo $order['Order']['id'];?>
		<td style="font-family:'Lucida Grande';">
			<?php echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['modified']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['price']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['amount']; ?></td>
		<td "><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>

		
	</tr>
	
	<?php endforeach; ?>
	</table></div>
</div>