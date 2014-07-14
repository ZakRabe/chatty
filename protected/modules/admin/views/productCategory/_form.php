<div class="form">

<?php 
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'product-category-form',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)); 

	// get frontend theme url
	$frontendTheme = Yii::app()->getModule('admin')->frontendTheme->baseURL;
	
	// check for theme/editor directory, set iframeBasePath
	$iframeBasePath = is_dir(Yii::app()->basePath.'/..'.$frontendTheme.'/editor') ? $frontendTheme.'/editor/' : null;
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'parent_id'); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row inputLabel">
		<?php echo $form->checkBox($model,'status'); ?>
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row clear">
		<?php echo $form->labelEx($model, 'image');?>
		<?php echo $form->fileField($model, 'image');?>
		<?php 
			if(!empty($model->image)){
				echo '<br/>';
				echo CHtml::image(Yii::app()->params->imageUploadPath.$model->image, '');
			}
		?>
		<?php echo $form->error($model, 'content_middle');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'keywords'); ?>
		<?php echo $form->textArea($model,'keywords',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'keywords'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description_detail'); ?>
		<?php echo $form->textArea($model,'description_detail',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description_detail'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'wymupdate')); ?>
	</div>

<?php 


	// generate wymeditor
	$this->widget( 'ext.EWYMeditor.EWYMeditor', array(
	  'target' => '[name="ProductCategory[description]"], [name="ProductCategory[description_detail]"]',
	  'plugins' => array( 'fullscreen', 'resizable', 'imgmgr'),
	  'options' => array(
		'iframeBasePath'=>$iframeBasePath,
		'stylesheet'=>$frontendTheme.'/css/editor.css',
		'containersItems'=>array(
			array('name'=>'P', 'title'=>'Paragraph', 'css'=>'wym_containers_p'),
			array('name'=>'H1', 'title'=>'Heading_1', 'css'=>'wym_containers_h1'),
			array('name'=>'H1', 'title'=>'Heading_2', 'css'=>'wym_containers_h2'),
			array('name'=>'H1', 'title'=>'Heading_3', 'css'=>'wym_containers_h3'),
			array('name'=>'H1', 'title'=>'Heading_4', 'css'=>'wym_containers_h4'),
			array('name'=>'BLOCKQUOTE', 'title'=>'Citation', 'css'=>'wym_containers_blockquote'),
			array('name'=>'DIV', 'title'=>'Page Divide', 'css'=>'wym_containers_div'),
		),
		'classesItems' => array(
		    array('name' => 'cta', 'title' => 'Call to Action Text', 'expr' => 'p')
			)
		// Check http://trac.wymeditor.org/trac/wiki/0.5/Customization for available options
	  ),
	));

	$this->endWidget(); 

?>

</div><!-- form -->