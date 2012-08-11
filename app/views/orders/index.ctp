<div class="overview">
	<?php echo $session -> flash();
		if(!isset($username))
		$username = ''; ?>
	<div class="two_column">
	<div class="orders_index">这些人要卖比特币</div>
	<table class="buy_sell">
	<tr>
			<th><?php echo $this->Paginator->sort('用户 ID','account_id');?></th>
			<th><?php echo $this->Paginator->sort('订单创建于', 'created');?></th>
			<th><?php echo $this->Paginator->sort('报价', 'price');?></th>
			<th><?php echo $this->Paginator->sort('数量', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('总额', 'total');?></th>
			<?php if($username) echo '<th class="actions">操作</th>'?>
	</tr>
	<?php
	$i = 0;
	foreach ($orders_sell as $order):
		$class = null;
		
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	
	<tr>

		<td style="padding-left:1px;">
			<?php echo $order['Account']['id'];
			//echo $this->Html->link($order['Account']['id'], array('controller' => 'accounts', 'action' => 'view', $order['Account']['id'])); ?>
		</td>
		<td><?php echo $order['Order']['created']; ?></td>
		<td><?php echo $order['Order']['price']; ?></td>
		<td><?php echo $order['Order']['amount']; ?></td>
		<td><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>
		<?php if ($username) 
		{ echo '<td class="actions">';?>
			<?php 
			if ($order['Account']['id']!=$account_id)
			echo $this->Html->link('买进', array('action' => 'match', $order['Order']['id']),array('class' => 'match_button'), sprintf('你确定要接受 # %s 买单?', $order['Order']['id']));
		echo '</td>';
		}?>
	</tr>
	<?php endforeach; ?>
	</table></div><div style="margin-left:2%;" class="two_column">
	<div class="orders_index">这些人要买比特币</div>
	<table class="buy_sell">
	<tr>
			<th><?php echo $this->Paginator->sort('用户 ID','account_id');?></th>
			<th><?php echo $this->Paginator->sort('订单创建于', 'created');?></th>
			<th><?php echo $this->Paginator->sort('报价', 'price');?></th>
			<th><?php echo $this->Paginator->sort('数量', 'amount');?></th>
			<th><?php echo $this->Paginator->sort('总额', 'total');?></th>
			<?php if($username) echo '<th class="actions">操作</th>'?>
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

		<td style="font-family:'Lucida Grande';">
			<?php echo $order['Account']['id']; ?>
		</td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['created']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['price']; ?></td>
		<td style="font-family:'Lucida Grande';"><?php echo $order['Order']['amount']; ?></td>
		<td><?php $total = (($order['Order']['price'])*($order['Order']['amount'])); echo "$total"; ?></td>
		<?php if ($username) 
		{ echo '<td class="actions">';?>
			<?php 
			if ($order['Account']['id']!=$account_id)
			echo $this->Html->link('卖出', array('action' => 'match', $order['Order']['id']),array('class' => 'match_button'), sprintf('你确定要接受 # %s 卖单?', $order['Order']['id']));	echo '</td>';
		}?>
		
		
	</tr>
	
	<?php endforeach; ?>
	</table></div>
</div>