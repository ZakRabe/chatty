<?php
$this->breadcrumbs=array(
	AdminModule::t('Profile Fields')=>array('admin'),
	AdminModule::t($model->title),
);
?>

<?php echo $this->renderPartial('/user/_menu'); ?>

<h1><?php echo AdminModule::t('View Profile Field #').$model->varname; ?></h1>

<?php echo $this->renderPartial('/user/_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Create Profile Field'),array('create'),array('class'=>'create')),
			CHtml::link(AdminModule::t('Update Profile Field'),array('update','id'=>$model->id),array('class'=>'update')),
			CHtml::linkButton(AdminModule::t('Delete Profile Field'),array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure to delete this item?','class'=>'create')),
		),
	));
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'varname',
		'title',
		'field_type',
		'field_size',
		'field_size_min',
		'required',
		'match',
		'range',
		'error_message',
		'other_validator',
		'widget',
		'widgetparams',
		'default',
		'position',
		'visible',
	),
)); ?>
