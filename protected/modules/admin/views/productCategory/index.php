<?php
$this->breadcrumbs=array(
	'Product Categories',
);

echo $this->renderPartial('../shop/_menu', array('parent' => $parent?$parent->title:''));

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/sortable.js',CClientScript::POS_HEAD);

$extra = '';
if(isset($_GET['id'])) $extra = '?parent_id='.$_GET['id'];


if($level <= Yii::app()->params['maxCategoryLevels']){
	echo $this->renderPartial('../product/_actions', array(
		'list'=> array(
			CHtml::link('Create Product Category',array('/admin/productCategory/create'.$extra),array('class'=>'create')),
		),
	));
}

?>

<h1 class="mainAdminHeader">Product Categories <?php echo $parent?' <span class="sub">&nbsp;under&nbsp;</span> <span class="item">'.$parent->title.'</span>':''; ?></h1>

<div class="span-8">
	<?php echo $this->renderPartial('_tree',array('parent'=>$parent,'list'=>$lists)); ?>
</div>

<div class="span-15">
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
	'summaryText'=>'Displaying {start}-{end} of {count} result(s).<br>Click Category name to view child-categories OR product count to view products.',
	'htmlOptions'=>array('rel' => '/admin/productCategory/'), //used in sortable.js to set the ajax url
	'columns'=>array(
		array(
			'name'=>'title',
			'value'=>($level < Yii::app()->params['maxCategoryLevels'])?'CHtml::link($data->title, Yii::app()->createUrl("admin/productCategory/",array("id"=>$data->id)))':'$data->title',
			'type'=>'raw',
		),
		array(
			'header'=>'Products',
			'value'=>(in_array($level, Yii::app()->params['productAtCategoryLevels']))?'CHtml::link(count($data->products), Yii::app()->createUrl("admin/product/",array("id"=>$data->id)))':'"n/a"',
			'type'=>'raw',
			'htmlOptions'=>array('class'=>'col-product-count'),
		),
		array(
			'name'=>'status',
			'value'=>'$data->status==1?"Active":"Not active"',
			'htmlOptions'=>array('class'=>'col-status'),
			'filter'=>CHtml::activeDropDownList($model,'status',array(''=>'',1=>'Active',0=>'Not active')),
		),
		array(
			'name'=>'created',
			'value'=>'date("d/m/Y",$data->created)',
			'htmlOptions'=>array('class'=>'col-date'),
			'filter'=>CHtml::activeTextField($model,'created'),
		),
		/*array(
			'name'=>'modified',
			'value'=>'date("d/m/Y",$data->modified)',
			'htmlOptions'=>array('class'=>'col-date'),
			'filter'=>CHtml::activeTextField($model,'modified'),
		),*/
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
</div>
