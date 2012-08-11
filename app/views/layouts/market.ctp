<html>
<head>

	<?php echo $this->Html->css('market.css');?>
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
	<div id="container">
		<div class="title" id="logo_med"></div><div class="title" id="title">
		<?php 
			switch  ($title_for_layout)
			{
				case '市场总览':
					echo '市场总览';
					break;
			
				case '历史交易':
					echo '历史交易';
					break;
				
				case '全部买单':
					echo '全部买单';
					break;
					
				case '全部卖单':
					echo '全部买单';
					break;
				
				default:
				break;
			}
		?>
		</div> 
		<div id="content">

			<?php $this->Session->flash();?>

			<?php echo $content_for_layout;?>

		</div>
	</div>
</body>
</html>
