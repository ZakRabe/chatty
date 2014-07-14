<?php
$this->pageTitle='Error - '.Yii::app()->name;
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="col-left">&nbsp;</div>
<div class="col-center">
	<h2>Error <?php echo $code; ?></h2>

	<div class="error">
	<?php echo CHtml::encode($message); ?>
	</div>
</div>
