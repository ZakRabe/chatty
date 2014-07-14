<?php
$this->breadcrumbs=array(
	'Product Option Groups'=>array('index'),
	'Create',
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>Create ProductOptionGroup</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>