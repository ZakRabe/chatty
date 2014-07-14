<?php
$this->breadcrumbs=array(
	'Product Categories'=>array('index'),
	'Create',
);

echo $this->renderPartial('../shop/_menu');
?>

<h1>Create Product Category <?php echo ($parent != '')?' - <strong>'.$parent.'</strong>':''; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>