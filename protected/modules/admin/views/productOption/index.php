<?php
$this->breadcrumbs=array(
	'Product Options',
);

echo $this->renderPartial('../shop/_menu');

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/sortable.js',CClientScript::POS_HEAD);

$extra = '';
if(isset($_GET['id'])) $extra = '?optionGroup_id='.$_GET['id'];
if($extra != ''){
	echo $this->renderPartial('../product/_actions', array(
		'list'=> array(
			CHtml::link('Create Product Option',array('/admin/productOption/create'.$extra),array('class'=>'create')),
		),
	));	
}

	
?>
<h1 class="mainAdminHeader">Product Options <?php echo $optionGroup?' <span class="sub">&nbsp;under&nbsp;</span> <span class="item">'.$optionGroup.'</span>':''; ?></h1>

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
	'htmlOptions'=>array('rel' => '/admin/productOption/'), //used in sortable.js to set the ajax url
	'columns'=>array(
		array(
			'name'=>'title',
			'value' => '$data->title',
		),
		array(
			'name'=>'global_code',
			'value'=>'$data->global_code',
			'header'=>'Code Segment',
		),
		array(
			'name'=>'global_price',
			'value'=>'$data->global_price',
			'header'=>'Global Price',
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
