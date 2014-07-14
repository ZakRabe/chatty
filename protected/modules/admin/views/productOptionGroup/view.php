<?php
$this->breadcrumbs=array(
	'Product Option Groups'=>array('index'),
	$model->title,
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>View ProductOptionGroup #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'code',
	),
)); ?>
