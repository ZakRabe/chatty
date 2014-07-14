<?php
$this->breadcrumbs=array(
	'Product Categories'=>array('index'),
	$model->title,
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>View ProductCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'type'=>'image',
			'value'=>Yii::app()->params->imageUploadPath.$model->image, 
			'label'=>'Uploaded Image'
		),
		'id',
		array(
			'name'=>'parent_id', 
			'label'=>'Parent Category',
			'value'=>($model->parent_id == 0)?"None":ProductCategory::model()->findByPK($model->parent_id)->title
		),
		'parent_id',
		'title',
		'keywords',
		array('name'=>'description','value'=>$model->description, 'type' => 'raw'),
		array('name'=>'description_detail','value'=>$model->description_detail, 'type' => 'raw'),
		array('name'=>'status','value'=>$model->status==1?'Active':'Not active'),
		array('name' => 'modified', 'value' =>date("d/m/Y",$model->modified)),
		array('name' => 'created', 'value' =>date("d/m/Y",$model->created)),
	),
)); ?> 
