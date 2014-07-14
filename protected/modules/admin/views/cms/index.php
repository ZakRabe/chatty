<?php
$this->breadcrumbs=array(
	'Manage Pages',
);
echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link('Create New Page',array('/admin/cms/create'),array('class'=>'create')),
		),
	));
?>

<?php echo $this->renderPartial('_menu'); ?>

<h1>Manage Pages</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cms-page-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('class'=>'col-id'),
		),
		array(
			'name'=>'title',
			'htmlOptions'=>array('style'=>'width:20em'),
		),
		//'tags',
		//'status',
		array(
			'name'=>'author_id',
			'type'=>'text',
			'value'=>'$data->author->username',
		),
		array(
			'name'=>'update_time',
			'type'=>'text',
			'value'=>'$data->update_time?date("Y-m-d g:ia",$data->update_time):""'
		),
		array(
			'name'=>'create_time',
			'type'=>'text',
			'value'=>'$data->create_time?date("Y-m-d g:ia",$data->create_time):""'
		),
		
		array(
			'class'=>'CButtonColumn',
			'template' => '{view}{update}',
		),
	),
)); ?>
