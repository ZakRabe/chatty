<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cms-page-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php 
		// get frontend theme url
		$frontendTheme = Yii::app()->getModule('admin')->frontendTheme->baseURL;
		// check for theme/editor directory, set iframeBasePath
		$iframeBasePath = is_dir(Yii::app()->basePath.'/..'.$frontendTheme.'/editor') ? $frontendTheme.'/editor/' : null;
		// generate wymeditor
		// TODO: Move these configurations into a central file for both blog & cms
		$this->widget( 'ext.EWYMeditor.EWYMeditor', array(
		  'model' => $model, // Your model
		  'attribute' => 'content', // Attribute for textarea
		  'plugins' => array( 'fullscreen', 'resizable', 'imgmgr'),
		  'options' => array(
			'iframeBasePath'=>$iframeBasePath,
			'stylesheet'=>$frontendTheme.'/css/editor.css',
			'containersItems'=>array(
				array('name'=>'P', 'title'=>'Paragraph', 'css'=>'wym_containers_p'),
				array('name'=>'H1', 'title'=>'Heading_1', 'css'=>'wym_containers_h1'),
				array('name'=>'H2', 'title'=>'Heading_2', 'css'=>'wym_containers_h2'),
				array('name'=>'H3', 'title'=>'Heading_3', 'css'=>'wym_containers_h3'),
				array('name'=>'H4', 'title'=>'Heading_4', 'css'=>'wym_containers_h4'),
				array('name'=>'BLOCKQUOTE', 'title'=>'Citation', 'css'=>'wym_containers_blockquote'),
				array('name'=>'DIV', 'title'=>'Page Divide', 'css'=>'wym_containers_div'),
			),
			// Check http://trac.wymeditor.org/trac/wiki/0.5/Customization for available options
		  ),
		)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<!--
	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>60)); ?>
		<p class="hint">Please separate different tags with commas.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>
	-->
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'wymupdate')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->