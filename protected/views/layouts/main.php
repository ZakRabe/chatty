<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" />


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div id="container">
<header>
	<a href="#">Our story</a> |
	<a href="#">Chat with us</a> |
	<a href="#">Facebook</a>
	<h1 class="replacement" id="logo">The Chatty Commuter</h1>
</header>

<div id="headline" class="replacement center">
One <i>chat</i> could change <i>everything</i>
</div>	

<div id="form" class="speech center">

	<form class="ajax" method="POST" action="/comment/create">
		<textarea name="text" class="comment input" rows="5" cols="45">Tell us how a simple chat on public transport made you smile, made you think, made your day, or ultimately changed your life...</textarea>
		<input class="name identity input" type="text" name="name" value="Name"> <hr>
		<input class="city identity input" type="text" name="city" value="City">
		<input class='email input' type="email" name="email" value="Email Address">
		<img id="submit" class="right" src="img/submit.png">
	</form>
</div>
	<?php
	$criteria = new CDbCriteria;
	$criteria->order = '`id` DESC';
	$comments = Comment::model()->findAll($criteria);
	$this->renderPartial('/comment/_list', array('comments'=>$comments));
 	?>
</div>



<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery-1.9.1.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
</body>

</html>