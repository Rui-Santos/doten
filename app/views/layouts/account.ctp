<html>
<head>

	<?php echo $this->Html->css('account.css');?>
	<?php echo $scripts_for_layout;?>
	<?php echo $this->Html->charset();?>
	<title>
		<?php echo $title_for_layout;?>
	</title>

	<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	
</head>

<body>
<?php echo $this -> element('nav_bar', array('location' => $location));?>
	<div id="container" style="text-align:center;margin-bottom:30px;">
		<div class="title" id="logo_med"></div>
		<div class="title" id="title"><?php if ($title_for_layout == '账户总览') echo '账户总览';?></div> 
		<div id="content">

			<?php $this->Session->flash();?>

			<?php echo $content_for_layout;?>

		</div>
		
	
	</div>
</body>
</html>
