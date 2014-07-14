<? echo CHtml::form('/admin/upload/create', 'post', array('enctype'=>'multipart/form-data')); ?>

<? echo CHtml::activeFileField($model, 'image'); ?>
<?php echo CHtml::submitButton('Submit'); ?>

<? echo CHtml::endForm(); ?> 