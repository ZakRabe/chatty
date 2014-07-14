<?php
$this->breadcrumbs=array(
	'Manage Pages'=>array('index'),
	$model->title,
);
?>

<?php echo $this->renderPartial('_menu'); ?>

<?php echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link(AdminModule::t('Update Page'),array('update','id'=>$model->id),array('class'=>'update')),
			CHtml::link(
				AdminModule::t('Delete Page'),
				'#',
				array('class'=>'delete','submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')
			),
		),
	));
?>

<h1>View Page #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		//'content',
		//'tags',
		//'status',
		array(
			'label'=>'Created',
			'type'=>'datetime',
			'value'=>$model->create_time,
		),
		array(
			'label'=>'Update',
			'type'=>'datetime',
			'value'=>$model->update_time,
		),
		array(
			'label'=>'Author',
			'type'=>'text',
			'value'=>$model->author->username,
		),
	),
)); ?>
