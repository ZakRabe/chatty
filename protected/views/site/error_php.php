<?php
$this->pageTitle='Error - '.Yii::app()->name;
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="col-left">&nbsp;</div>
<div class="col-center">
	<h2>Woops!</h2>
	<p>Sorry an error has occurred. Our technical staff have been notified and will fix this as soon as possible. 
	If you have any questions, please <a href="<?php echo $this->createUrl('site/contact'); ?>">contact us</a>.</p>
</div>
