<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary(array($model)); ?>
	
	<div class="column span-12">

		
		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username',array('size'=>30,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'password',array('label'=>'Password')); ?>
			<?php echo $form->passwordField($model,'password',array('size'=>30,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'repeat_password',array('label'=>'Repeat New Password')); ?>
			<?php echo $form->passwordField($model,'repeat_password',array('size'=>30,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'repeat_password'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>


		<div class="row">
			<?php echo $form->labelEx($model,'status'); ?>
			<?php echo $form->dropDownList($model,'status',array('1'=>'Active','0'=>'Not active','1'=>'Active')); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	
	
	<div class="row buttons clear">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
