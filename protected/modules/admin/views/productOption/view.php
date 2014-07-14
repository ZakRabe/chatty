<?php
$this->breadcrumbs=array(
	'Product Options'=>array('index'),
	$model->title,
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>View ProductOption #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'group_id',
		'title',
		'global_price',
		'global_code',
	),
)); ?>
