<?php
$this->breadcrumbs=array(
	AdminModule::t('Profile Fields')=>array('admin'),
	AdminModule::t('Create'),
);
?>
<h1><?php echo AdminModule::t('Create Profile Field'); ?></h1>

<?php echo $this->renderPartial('/user/_menu',array(
		'list'=> array(),
	)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>