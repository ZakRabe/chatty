<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title,
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>View Product</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'code',
		array('name'=>'global_price','value'=>$model->global_price==1?'Yes':'No'),
		array('name'=>'price','value'=>'$'.number_format($model->productPrices[0]->price, 2, '.', ','), 'visible' => $model->global_price),
		array('name'=>'sale','value'=>$model->productPrices[0]->sale?'Yes':'No', 'visible' => $model->global_price),
		'keywords',
		array('name'=>'description','value'=>$model->description, 'type' => 'raw'),
		array('name'=>'description_detail','value'=>$model->description_detail, 'type' => 'raw'),
		array('name'=>'specs','value'=>$model->specs, 'type' => 'raw'),
		'weight',
		'feature',
		'views',
		'impressions',
		
		array('name'=>'status','value'=>$model->status==1?'Active':'Not active'),
		array('name' => 'modified', 'value' =>date("d/m/Y",$model->modified)),
		array('name' => 'created', 'value' =>date("d/m/Y",$model->created)),
	),
)); ?>
