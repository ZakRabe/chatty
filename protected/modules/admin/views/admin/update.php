<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	$model->username=>array('view','id'=>$model->id),
	'Update',
);

echo $this->renderPartial('_menu');

?>

<h1>Update Administrator <strong><?php echo $model->username; ?></strong></h1>

<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
		)); ?>
