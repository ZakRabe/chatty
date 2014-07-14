<?php
$this->breadcrumbs=array(
	'Products',
);

echo $this->renderPartial('../shop/_menu');

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/sortable.js',CClientScript::POS_HEAD);

$append = isset($_GET['id']) ? '?category_id='.$_GET['id'] : '';
echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link('Create Product',array('/admin/product/create'.$append),array('class'=>'create')),
		),
	));
?>
<h1 class="mainAdminHeader">Products <?php echo $category?' <span class="sub">&nbsp;under&nbsp;</span> <span class="item">'.$category->title.'</span>':''; ?></h1>


<div class="span-8">
	<?php echo $this->renderPartial('../productCategory/_tree',array(
			'parent'=>$category,
			'list'=>$lists,
			'route'=>'product/'
		)); ?>
</div>

<div class="span-15">
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->searchAdmin(),
	'filter'=>$model,
	'pager'=>array(
		'class'=>'CLinkPager',
	),
	'beforeAjaxUpdate'=>'function(id,options){
		var url = unescape(options.url);
		var search = "Product[category_id_filter]=";
		var loc = url.indexOf(search);
		var categoryId = 0;
		
		url = url.substring(loc+search.length);
		categoryId = url.substring(0, url.indexOf("&"))
		
		if(categoryId != ""){
			$("a.createProduct").attr("href","/admin/product/create?category_id="+categoryId)
		}else{
			$("a.createProduct").attr("href","/admin/product/create")
		}
	}',
	'afterAjaxUpdate'=>'function(id,options){
		setUpSortable()
	}',
	'htmlOptions'=>array('rel' => '/admin/product/'), //used in sortable.js to set the ajax url
	'columns'=>array(
		array(
			'name'=>'title',
			'value'=>'$data->title',
		),
		array(
			'name'=>'status',
			'value'=>'$data->status==1?"Active":"Inactive"',
			'htmlOptions'=>array('class'=>'col-status'),
			'filter'=>CHtml::activeDropDownList($model,'status',array(''=>'',1=>'Active',0=>'Inactive')),
		),
		array(
			'name'=>'featured',
			'value'=>'$data->feature==1?"<span class=\"tick\">Featured</span>":"&nbsp;"',
			'htmlOptions'=>array('class'=>'col-icon'),
			'filter'=>CHtml::activeDropDownList($model,'feature',array(''=>'',1=>'Featured',0=>'Not')),
			'type'=>'raw',
		),
		array(
			'name'=>'modified',
			'value'=>'date("d/m/Y",$data->modified)',
			'htmlOptions'=>array('class'=>'col-date'),
		),
		/*
		array(
			'class'=>'CButtonColumn',
		),
		*/
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
)); ?>
</div>
