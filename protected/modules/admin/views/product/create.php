<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Create',
);

echo $this->renderPartial('../shop/_menu');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/themes/reactor/js/supporting.js',CClientScript::POS_HEAD);
?>
<h1>Create Product</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'categoriesList' => $categoriesList, 'optionsList' => $optionsList)); ?>