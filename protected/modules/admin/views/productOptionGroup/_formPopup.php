<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-option-group-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>20,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->dropDownList($model,'code',array('prefix'=>'Prefix','suffix'=>'Suffix')); ?> 
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row buttons">
		<?php 
		echo CHtml::ajaxSubmitButton(
			$model->isNewRecord ? 'Create' : 'Save', 
			'/admin/productOptionGroup/create',
			array('success'=>'js: function(response) {
				var data = $.parseJSON(response);
				
				//console.log(data.status)
				
				if(data.status == "failure"){
					$("#AddNewOptionGroup").html(data.content)
				}
				if(data.status == "success"){
					generateOptionGroup(data.id, data.title)
					$("#AddNewOptionGroup").dialog("close")
				}
            }'),
            array('id'=>'saveOptionGroup')
		);

		 ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->