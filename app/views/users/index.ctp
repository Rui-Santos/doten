<p>你好,  <?php echo($username); ?></p>
<p>您的帐户ID是 <?php $account_id = $this -> Session ->read('account_id'); echo($account_id); ?></p>

<?php echo $html->link('浏览订单', array('controller' => 'Orders', 'action' => 'index')); ?>
<?php echo $html->link('我的帐户', array('controller' => 'Accounts', 'action' => 'index')); ?>
<?php echo $html->link('退出', array('action' => 'logout')); ?>