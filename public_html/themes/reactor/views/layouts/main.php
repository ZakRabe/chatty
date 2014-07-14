<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/screen.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/main.css'); ?>
	
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald|Droid+Sans|Droid+Sans+Mono">
	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css">
	<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" /><![endif]-->
	
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/modernizr-2.5.3.min.js"></script>
	
</head>

<body class="<?php echo Yii::app()->controller->id; ?> <?php echo php_uname('n')=='arnie'?'staging':'production'; ?>">
	
	<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
	
	<div id="wrap">
		
		<div id="header">
			<div id="location-display"><?php echo Yii::app()->user->isGuest ?'':'CMS // <strong>'.Yii::app()->name.'</strong>'; ?></div>
			<div id="logo">Website Management (<?php echo CHtml::encode(Yii::app()->name); ?>)</div>
		</div><!-- header -->

		<div id="mainmenu">
			<?php 
			if (!Yii::app()->user->isGuest) {
				$this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array(
							'url'=>'/admin', 
							'label'=>'Home', 
							'active'=>Yii::app()->controller->id=='default',
						),
						array(
							'url'=>'/admin/cms', 
							'label'=>'Pages', 
							'active'=>Yii::app()->controller->id=='cms',
						),
						array(
							'url'=>'/admin/shop', 
							'label'=>'Shop', 
							'active'=>in_array(Yii::app()->controller->id, array(
								'shop',
								'productCategory',
								'productOption',
								'productOptionGroup',
								'product',
							)),
						),
						array(
							'url'=>array('/admin/admin'), 
							'label'=>'Admin',
							'active'=>(Yii::app()->controller->id=='admin'),
						),
						array(
							'url'=>Yii::app()->getModule('admin')->logoutUrl, 
							'label'=>'Logout ('.Yii::app()->user->name.')', 
						),
					),
				)); 
			}
			?>
		</div><!-- mainmenu -->
		
		<div class="container" id="page">
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
				'homeLink'=>CHtml::link('Administration', array('/admin')),
			)); ?><!-- breadcrumbs -->

			<?php echo $content; ?>

		</div><!-- page -->
	</div><!-- wrap -->
	
	<div id="footer">
		<div class="container">
		<div class="column span-18">
			Time: <?php echo date("r"); ?><br />
			Peak Memory Usage: <?php echo round(memory_get_peak_usage()/1048576,2); ?>MB<br />
			CPU Load: <?php echo file_get_contents("/proc/loadavg"); ?>
		</div>
		<div class="column span-7 last">
			Copyright &copy; <?php echo date('Y'); ?> Reactor Digital<br/>
			All Rights Reserved.<br/>
			<?php echo Yii::powered(); ?>
		</div>
	</div><!-- footer -->
	
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/main.js"></script>

</body>
</html>
