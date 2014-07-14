<?php
$this->breadcrumbs=array(
	'Product Options'=>array('index'),
	'Create',
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>Create ProductOption</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>