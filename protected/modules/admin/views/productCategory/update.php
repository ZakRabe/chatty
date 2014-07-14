<?php
$this->breadcrumbs=array(
	'Product Categories'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>Update ProductCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>