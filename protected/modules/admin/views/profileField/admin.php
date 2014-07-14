<?php
$this->breadcrumbs=array(
	AdminModule::t('Profile Fields')=>array('admin'),
	AdminModule::t('Manage'),
);
?>

<?php echo $this->renderPartial('/user/_menu'); ?>

<h1><?php echo AdminModule::t('Manage Profile Fields'); ?></h1>

<?php 
echo $this->renderPartial('/user/_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Create Profile Field'),array('create'),array('class'=>'create')),
		),
	)); 
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		'varname',
		array(
			'name'=>'title',
			'value'=>'AdminModule::t($data->title)',
		),
		'field_type',
		'field_size',
		//'field_size_min',
		array(
			'name'=>'required',
			'value'=>'ProfileField::itemAlias("required",$data->required)',
		),
		//'match',
		//'range',
		//'error_message',
		//'other_validator',
		//'default',
		'position',
		array(
			'name'=>'visible',
			'value'=>'ProfileField::itemAlias("visible",$data->visible)',
		),
		//*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
