<html>
<head>
	<?php echo $this->Html->css('login.css');?>
	<?php echo $scripts_for_layout;?>
	<?php echo $this->Html->charset();?>
	<title>
		<?php echo $title_for_layout;?>
	</title>

	<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />

</head>

<body>
<?php echo $this -> element('back_to_home', array('location' => $location));?>
	<div id="container">
		<div id="logo_med"></div>
		<div id="content">

			<?php $this->Session->flash();?>

			<?php echo $content_for_layout;?>

		</div>
	</div>
</body>
</html>