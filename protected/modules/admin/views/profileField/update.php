<?php
$this->breadcrumbs=array(
	AdminModule::t('Profile Fields')=>array('admin'),
	$model->title=>array('view','id'=>$model->id),
	AdminModule::t('Update'),
);
?>

<?php echo $this->renderPartial('/user/_menu'); ?>

<h1><?php echo AdminModule::t('Update ProfileField ').$model->id; ?></h1>

<?php echo $this->renderPartial('/user/_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Create Profile Field'),array('create'),array('class'=>'create')),
			CHtml::link(AdminModule::t('View Profile Field'),array('view','id'=>$model->id),array('class'=>'read')),
		),
	));
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>