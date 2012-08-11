<html>
<head>

	<?php echo $this->Html->css('default.css');?>
	<?php echo $scripts_for_layout;?>
	<?php echo $this->Html->charset();?>
	<title>
		<?php echo $title_for_layout;?>
	</title>

	<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	
</head>

<body>
<?php
	if(!isset($location))
		$location = NULL;
	echo $this -> element('nav_bar', array('location' => $location));
?>
	<div id="container">
		<div class="title" id="logo_med"></div><div class="title" id="title"></div> 
		<div id="content">

			<?php $this->Session->flash();?>

			<?php echo $content_for_layout;?>

		</div>
	</div>
	<?php echo $this -> element('footer');?>
</body>
</html>
