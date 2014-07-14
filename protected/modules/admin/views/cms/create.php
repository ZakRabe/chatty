<?php
$this->breadcrumbs=array(
	'Manage Pages'=>array('index'),
	'Create Page',
);
?>

<?php echo $this->renderPartial('_menu'); ?>

<?php echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Cancel'),array('index'),array('class'=>'cancel')),
		),
	));
?>

<h1>Create Page</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>