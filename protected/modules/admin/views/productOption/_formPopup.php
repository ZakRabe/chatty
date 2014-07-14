<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-option-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'group_id'); ?>
	

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>20,'maxlength'=>250)); ?>
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
		<?php 
		echo CHtml::ajaxSubmitButton(
			$model->isNewRecord ? 'Create' : 'Save', 
			'/admin/productOption/create',
			array('success'=>'js: function(response) {
				var data = $.parseJSON(response);
				
				//console.log(data.status)
				
				if(data.status == "failure"){
					$("#AddNewOption").html(data.content)
				}
				if(data.status == "success"){
					generateOption(data.group_id, data.id, data.title, data.global_price, data.global_code)
					$("#AddNewOption").dialog("close")
				}
            }'),
            array('id'=>'saveOption')
		);

		 ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->