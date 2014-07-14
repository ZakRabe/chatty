<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-option-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<!--
	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->textField($model,'group_id'); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>
	-->
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'global_price'); ?>
		<?php echo $form->textField($model,'global_price',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'global_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'global_code'); ?>
		<?php echo $form->textField($model,'global_code',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'global_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->