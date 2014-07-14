<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/screen.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/main.css'); ?>
	<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->theme->baseUrl.'/css/reports.css'); ?>

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Basic|Inconsolata|PT+Sans|Droid+Sans+Mono" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	
	<style type="text/css" media="print">
		#backbutton { display: none; }
	</style>
</head>

<body class="<?php echo Yii::app()->controller->id; ?> <?php echo php_uname('n')=='arnie'?'staging':'production'; ?>">
	
<?php echo $content; ?>

<div id="footer">
	<a href="<?php echo $this->createUrl('labReports/index'); ?>" id="backbutton">&lt;&lt; Back</a><br>
	Copyright &copy; <?php echo date('Y'); ?> Reactor Digital<br/>
	All Rights Reserved.<br/>
	<?php echo Yii::powered(); ?>
</div><!-- footer -->

</body>
</html>
