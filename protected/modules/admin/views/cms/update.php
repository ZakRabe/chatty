<?php
$this->breadcrumbs=array(
	'Manage Pages'=>array('index'),
	'Update Page',
);
?>

<?php echo $this->renderPartial('_menu'); ?>

<?php echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Cancel'),array('index'),array('class'=>'cancel')),
		),
	));
?>

<h1>Update Page #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>