<?php
$this->breadcrumbs=array(
	'Users',
);

echo $this->renderPartial('_menu');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Administrators</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'pager'=>array(
		'class'=>'CLinkPager',
		'pageSize'=>15,
	),
	'columns'=>array(
		array(
			'name'=>'username',
			'value'=>'$data->username',
			'header'=>'Username',
		),
		array(
			'name'=>'email',
			'value'=>'$data->email',
			'htmlOptions'=>array('class'=>'col-email'),
			'header'=>'Email',
		),
		array(
			'name'=>'status',
			'value'=>'$data->status==1?"Active":"Not active"',
			'htmlOptions'=>array('class'=>'col-status'),
			'filter'=>CHtml::activeDropDownList($model,'status',array(''=>'',0=>'Active',1=>'Not active')),
			'header'=>'Status',
		),
		array(
			'name'=>'createtime',
			'value'=>'date("d/m/Y",$data->createtime)',
			'header'=>'Registered',
			'htmlOptions'=>array('class'=>'col-date'),
			'filter'=>CHtml::activeTextField($model,'createtime'),
		),
		array(
			'class'=>'CButtonColumn'
		),
	),
)); ?>
