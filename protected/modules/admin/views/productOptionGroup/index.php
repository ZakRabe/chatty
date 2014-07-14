<?php
$this->breadcrumbs=array(
	'Product Option Groups',
);

echo $this->renderPartial('../shop/_menu');

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/sortable.js',CClientScript::POS_HEAD);

echo $this->renderPartial('../product/_actions', array(
		'list'=> array(
			CHtml::link('Create Product Option Group',array('/admin/productOptionGroup/create'),array('class'=>'create')),
		),
	));
	
?>

<h1>Product Option Groups</h1>

<?php 

	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'pager'=>array(
		'class'=>'CLinkPager',
		'pageSize'=>15,
	),
	'afterAjaxUpdate'=>'function(id,options){
		setUpSortable()
	}',
	'htmlOptions'=>array('rel' => '/admin/productOptionGroup/'), //used in sortable.js to set the ajax url
	'columns'=>array(
		array(
			'name'=>'title',
			'value' => 'CHtml::link($data->title, Yii::app()->createUrl("admin/productOption/",array("id"=>$data->id)))',
        	'type'  => 'raw',
		),
		array(
			'name'=>'code',
			'value'=>'$data->code=="prefix"?"Prefix":"Suffix"',
			'filter'=>CHtml::activeDropDownList($model,'code',array(''=>'','prefix'=>'Prefix','suffix'=>'Suffix')),
			'header'=>'Code',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{upDown}{view}{update}{delete}',
			'buttons'=>array(
				'upDown' => array(
					'label'=>'Move Up/Down',
					'url'=>'$data->id',
					'options' => array('class' => 'upDown'),
				),
			),
		),
	),
)); 


?>

