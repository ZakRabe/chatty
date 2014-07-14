<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

echo $this->renderPartial('../shop/_menu');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/supporting.js',CClientScript::POS_HEAD);
?>

<h1>Update Product: <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'categoriesList' => $categoriesList, 'optionsList' => $optionsList)); ?>