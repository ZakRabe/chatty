<div class="form">
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="span-15 ">

		<div class="row">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>250)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'code'); ?>
			<?php echo $form->textField($model,'code',array('size'=>10,'maxlength'=>250)); ?>
			<?php echo $form->error($model,'code'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'weight'); ?>
			<?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>250)); ?>
			<?php echo $form->error($model,'weight'); ?>
		</div>
		
		<div class="checkbox-list">
			
			<div class="row inputLabel">
				<?php echo $form->checkBox($model,'status'); ?>
				<?php echo $form->labelEx($model,'status'); ?>
				<?php echo $form->error($model,'status'); ?>
			</div>
			
			<div class="row inputLabel clear">
				<?php echo $form->checkBox($model,'feature'); ?>
				<?php echo $form->labelEx($model,'feature'); ?>
				<?php echo $form->error($model,'feature'); ?>
			</div>
			
		</div>
		
		<div class="row clear">
			<?php echo $form->labelEx($model,'keywords'); ?>
			<?php echo $form->textField($model,'keywords',array('size'=>50)); ?>
			<?php echo $form->error($model,'keywords'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'description'); ?>
			<?php echo $form->textArea($model,'description',array('rows'=>3, 'cols'=>50)); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
		
		

	</div>
	
	<div class="span-8 ">
		<div class="row">
			<?php echo $form->labelEx($model,'category_ids'); ?>
			<div id="cat-chooser">
<?php

switch(Yii::app()->params['categoryDisplayMode']){
	case 'dropList':
		echo $form->dropDownList($model,'category_ids',array(''=>'')+$categoriesList,array('encode'=>false ,'options' => $optionsList));
	break;
	
	case 'dropListMulti':
		echo $form->dropDownList($model,'category_ids',$categoriesList,array('encode'=>false,'multiple'=>'multiple', 'size'=>'10', 'class' => 'multi','options' => $optionsList));
	break;
	
	case 'checkBoxes':
		$isStart = true;
		$doEnd=false;
		$count = 0;
		foreach($categoriesList as $catListItemId=>$catListItem)
		{
			if($optionsList[$catListItemId]['level'] == 0)
			{
				if($doEnd)
					echo '</div>'."\n";
				echo '<div class="categoryWrapper">'."\n";
				$doEnd = true;
			}
			
			if(!isset($optionsList[$catListItemId]['disabled']) 
				|| (isset($optionsList[$catListItemId]['disabled']) 
				&& $optionsList[$catListItemId]['disabled'] == false))
			{
				echo "<div class=\"row inputLabel clear\">";
				echo CHtml::checkBox('Product[category_ids][]',in_array($catListItemId, $model->category_ids),array('id'=>'category_id_'.$count, 'value' => $catListItemId, 'class' => 'level'.$optionsList[$catListItemId]['level']))."\n";
				echo CHtml::label($catListItem, 'category_id_'.$count)."\n";
				echo "</div>\n";
			}
			else
				echo "<div class=\"row clear\"><strong>".$catListItem."</strong></div>\n";
			$count++;
		}
		if($count > 0) 
			echo "</div><div style=\"clear:both;\"></div>\n";
	break;
}
echo $form->error($model,'category_ids');
?>
			</div>
		</div>
	</div>
	
	<h2 class="clear">Pricing / Options</h2>
	
	<div class="row inputLabel clear">
		<?php echo $form->checkBox($model,'global_price'); ?>
		<?php echo $form->labelEx($model,'global_price'); ?>
		<?php echo $form->error($model,'global_price'); ?>
	</div>
	
	<div class="row priceSale clear">
		<div class="row labelInput">
			<?php echo $form->labelEx($model,'price'); ?>
			<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>12)); ?> 
			<?php echo $form->error($model,'price'); ?>
		</div>
		
		<div class="row inputLabel">
			<?php echo $form->checkBox($model,'isSale'); ?>
			<?php echo $form->labelEx($model,'isSale'); ?>
			<?php echo $form->error($model,'isSale'); ?>
		</div>
	</div>
	
	<div class="row options clear">
		<div id="optionGroups">
<?php
$optionsGroup = ProductOptionGroup::model()->getDropList('create');

$addedOptionGroups = array();
foreach($model->optionGroup_ids as $optionGroupId){

	echo '<fieldset id="groupId_'.$optionGroupId.'">'."\n";
	
	echo '<legend>'."\n";
	echo 	'<input id="ytoptionGroupActive_'.$optionGroupId.'" type="hidden" name="Product[options]['.$optionGroupId.'][optionGroupSettings][optionGroupActive]" value="0">'."\n";
	echo 	'<input id="optionGroupActive_'.$optionGroupId.'" type="checkbox" '.($model->options[$optionGroupId]['optionGroupSettings']['optionGroupActive']?'checked="checked"':'').' value="1" name="Product[options]['.$optionGroupId.'][optionGroupSettings][optionGroupActive]">'."\n";
	echo 	'<span class="optionGroupActive"></span>'.$optionsGroup[$optionGroupId]['title']."\n";
	echo '</legend>'."\n";
	
	echo '<input type="hidden" value="'.$optionGroupId.'" name="Product[optionGroup_ids][]">'."\n";
			
	echo '<p><span class="option">Option</span><span class="price">Price</span><span class="code">Product Code Prefix/Sufix</span><span class="sale">On Sale</span></p>'."\n";
	
	echo '<div class="optionsWrapper">'."\n";
	
	$options = ProductOption::model()->getDropList($optionGroupId,'create');
	$addedOptions = array();
	if(isset($model->options[$optionGroupId])){
		
		foreach($model->options[$optionGroupId] as $optionId=>$optionInfo){
			if($optionId != 'optionGroupSettings'){
				echo '<div id="groupOption_groupID_'.$optionGroupId.'_optionID_'.$optionId.'">'."\n";
			
				if(isset($optionInfo['options_new'])) echo 	'<input type="hidden" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_new]" value="1">';
				if(isset($optionInfo['options_delete'])) echo 	'<input type="hidden" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_delete]" value="1">';
				
				echo 	'<div class="optionColumn option">'."\n";
				echo 		'<input id="ytoption_status_'.$optionId.'" type="hidden" value="0" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_status]">'."\n";
				echo 		'<input id="option_status_'.$optionId.'" class="canBeDisabled" type="checkbox" '.($optionInfo['options_status']?'checked="checked"':'').' value="1" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_status]">'."\n";
				echo 		'<label for="option_status_'.$optionId.'">'.$options[$optionId]['title'].'</label>'."\n";
				echo 	'</div>'."\n";
				
				$priceId = 'Product_options_'.$optionGroupId.'_'.$optionId.'_options_price';
				$htlmOptions = array('size'=>10, 'maxlength'=>20, 'class'=>'canBeDisabled');
				if($model->getErrors($priceId)) $htlmOptions['class'] = 'error canBeDisabled';
				
				echo 	'<div class="optionColumn price">'."\n";
				echo 	CHtml::textField('Product[options]['.$optionGroupId.']['.$optionId.'][options_price]', $optionInfo['options_price'],$htlmOptions);
				//echo 		'<input id="option_price_'.$optionId.'" type="text" size="10" maxlength="20" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_price]" value="'.$optionInfo['options_price'].'">'."\n";
				echo $form->error($model,$priceId);
				echo 	'</div>'."\n";
				
				echo 	'<div class="optionColumn code">'.(($optionsGroup[$optionGroupId]['code']=='prefix')?$options[$optionId]['code'].$model->code:$model->code.$options[$optionId]['code']).'</div>'."\n";
				
				echo 	'<div class="optionColumn sale">'."\n";
				echo 		'<input id="ytoption_sale_'.$optionId.'" type="hidden" value="0" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_sale]">'."\n";
				echo 		'<input id="option_sale_'.$optionId.'" class="canBeDisabled" type="checkbox" '.($optionInfo['options_sale']?'checked="checked"':'').' value="1" name="Product[options]['.$optionGroupId.']['.$optionId.'][options_sale]">'."\n";
				echo 	'</div>'."\n";
				
				echo 	'<div class="clear"></div>'."\n";
				
				echo '</div>'."\n";
				
				$addedOptions[] = $optionId;
			}
			
		}
	}
	
	echo '</div>'."\n";
	
	
	echo '<select class="Product_option_ids" name="Product[option_ids_drop]" title="'.$optionsGroup[$optionGroupId]['title'].'" rel="'.$optionGroupId.'">'."\n";
	echo '	<option value="" selected="selected">** Add Option **</option>'."\n";
	
	$added = 0;
	foreach($options as $optionId=>$optionInfo){
		if(!in_array($optionId, $addedOptions)){
			echo '<option code="'.$optionInfo['code'].'" price="'.$optionInfo['price'].'" value="'.$optionId.'">'.$optionInfo['title'].'</option>'."\n";
			$added ++;
		}
	}
	
	
	echo '	<option value="-" disabled="disabled">--------------------</option>'."\n";
	if($added > 0) echo '	<option value="all">Add All Options</option>'."\n";
	echo '	<option value="new">Add New Option</option>'."\n";
	echo '</select>'."\n";
	echo $form->error($model,'options_'.$optionGroupId);
	
	echo '</fieldset>'."\n";
	
	$addedOptionGroups[] = $optionGroupId;
}
echo '</div>';


$filteredOptionGroups = array();
$added = 0;
foreach($optionsGroup as $optionGroupId => $optionGroupInfo){
	if(!in_array($optionGroupId, $addedOptionGroups)){
		$filteredOptionGroups[$optionGroupId] = $optionGroupInfo['title'];
		$added ++;
	}
}

echo CHtml::dropDownList('Product_optionGroup_ids_drop','',array(''=>'** Add Option Group **')+$filteredOptionGroups+ array('-'=>'--------------------', 'new'=>'Add New Option Group'), array('encode'=>false));
?>
	</div>
	
	<h2 class="clear">Images</h2>
	<?php
		echo CHtml::link('Upload images', '#', array(
		   'onclick'=>'$("#fileupload .cancel").trigger("click"); $("#imageUpload").dialog("open"); imageUploadInit(); return false;',
		));
	?>
	
	<ul id="sortable" class="productImages">
<?php
if ($model->productImages){
	
	$count = 0;
	foreach ($model->productImages as $image){
?>
		<li><?php echo $image->output(130,130,'inside') ?>
			<input id="uploadedImage_<?php echo $count ?>" type="hidden" value="<?php echo $image->image ?>" name="Product[images][]">
			<div class="overlay">
				<table>
					<tbody>
						<tr>
							<th>Type</th>
							<td><?php echo $image->type; ?></td>
						</tr>
						<tr>
							<th>Dimensions</th>
							<td><?php echo $image->width."&times;".$image->height; ?></td>
						</tr>
						<tr>
							<th>Size</th>
							<td><?php echo $image->size; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>
<?php
		$count++;
	}
}
?>
	</ul>
	
<!--
	<div class="row clear">
		<?php echo $form->labelEx($model, 'gridImage');?>
		<?php echo $form->fileField($model, 'gridImage');?>
		<?php 
			if(!empty($model->gridImage)){
				echo '<br/>';
				echo CHtml::image(Yii::app()->params->imageUploadPath.$model->gridImage, '');
			}
		?>
		<?php echo $form->error($model, 'gridImage');?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'detailImage');?>
		<?php echo $form->fileField($model, 'detailImage');?>
		<?php 
			if(!empty($model->detailImage)){
				echo '<br/>';
				echo CHtml::image(Yii::app()->params->imageUploadPath.$model->detailImage, '');
			}
		?>
		<?php echo $form->error($model, 'detailImage');?>
	</div>
-->
	
	<h2 class="clear">Detailed Description</h2>
	
	<div class="row">
		<?php echo $form->textArea($model,'description_detail',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description_detail'); ?>
	</div>

	<?php 
		if(Yii::app()->params['displaySpecifications']){
			echo '<h2 class="clear">Specs</h2>';
			echo '<div class="row">';
			echo $form->textArea($model,'specs',array('rows'=>6, 'cols'=>50));
			echo $form->error($model,'specs');
			echo '</div>';
		}
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'wymupdate')); ?>
	</div>
	
	<?php $this->endWidget('CActiveForm'); ?>
</div><!-- form -->

<?php 
// get frontend theme url
$frontendTheme = Yii::app()->getModule('admin')->frontendTheme->baseURL;
// check for theme/editor directory, set iframeBasePath
$iframeBasePath = is_dir(Yii::app()->basePath.'/..'.$frontendTheme.'/editor') ? $frontendTheme.'/editor/' : null;
// generate wymeditor
$this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'target' => '[name="Product[description_detail]"], [name="Product[specs]"]',
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
	// Check http://trac.wymeditor.org/trac/wiki/0.5/Customization for available options
  ),
));


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'AddNewOptionGroup',
	// additional javascript options for the dialog plugin
	'options'=>array(
		'title'=>'Add New Option Group',
		'autoOpen'=>false,
		'modal' => true,
		'width' => 300,
		'close' => 'js:function(event, ui) { 
			$("#Product_optionGroup_ids").val("")
		}',  
	),
	
));
echo $this->renderPartial('../productOptionGroup/_formPopup', array('model'=>ProductOptionGroup::model()));
$this->endWidget('zii.widgets.jui.CJuiDialog');


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'AddNewOption',
	// additional javascript options for the dialog plugin
	'options'=>array(
		'title'=>'Add New Option',
		'autoOpen'=>false,
		'modal' => true,
		'width' => 300,
		'close' => 'js:function(event, ui) {
			$("#Product_option_ids").val("")
		}',    
	),
	
));
echo $this->renderPartial('../productOption/_formPopup', array('model'=>ProductOption::model()));
$this->endWidget('zii.widgets.jui.CJuiDialog');


Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/load-image.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/tmpl.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/jquery.fileupload.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/jquery.fileupload-fp.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/jquery.fileupload-ui.js', CClientScript::POS_END);

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'imageUpload',
	// additional javascript options for the dialog plugin
	'options'=>array(
		'title'=>'Upload Images',
		'autoOpen'=>false,
		'modal' => true,
		'width' => 800,
		'height'=>500,
		'buttons'=>array(
			'Start Upload'=>'js:function(){ $("#fileupload .start").trigger("click"); }',
			'Cancel'=>'js:function() { $("#fileupload .cancel").trigger("click"); $(this).dialog("close"); }',
			'Ok'=>'js:function() {$(this).dialog("close"); addImages(); }',
		),
	),
)); ?>
	<!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="/upload/index.php" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="fileinput-button">
                    <span>Drag and drop files or click here...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="start" style="display:none">
                    <span>Start upload</span>
                </button>
                <button type="reset" class="cancel" style="display:none">
                    <span>Cancel upload</span>
                </button>
                <!--<button type="button" class="delete">
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">-->
            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active ui-widget-content" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
		<!-- modal-gallery is the modal dialog used for the image gallery -->
		<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h3 class="modal-title"></h3>
			</div>
			<div class="modal-body"><div class="modal-image"></div></div>
			<div class="modal-footer">
				<a class="btn modal-download" target="_blank">
					<i class="icon-download"></i>
					<span>Download</span>
				</a>
				<a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
					<i class="icon-play icon-white"></i>
					<span>Slideshow</span>
				</a>
				<a class="btn btn-info modal-prev">
					<i class="icon-arrow-left icon-white"></i>
					<span>Previous</span>
				</a>
				<a class="btn btn-primary modal-next">
					<span>Next</span>
					<i class="icon-arrow-right icon-white"></i>
				</a>
			</div>
		</div>
    </form>
	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload fade">
			<td class="preview"><span class="fade"></span></td>
			<td class="name"><span>{%=file.name%}</span></td>
			<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
			{% if (file.error) { %}
				<td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else if (o.files.valid && !i) { %}
				<td>
					<div class="progress progress-success progress-striped active ui-widget-content" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
				</td>
				<td class="start">{% if (!o.options.autoUpload) { %}
					<button class="btn btn-primary">
						<i class="icon-upload icon-white"></i>
						<span>{%=locale.fileupload.start%}</span>
					</button>
				{% } %}</td>
			{% } else { %}
				<td colspan="2"></td>
			{% } %}
			<td class="cancel">{% if (!i) { %}
				<button class="btn btn-warning">
					<i class="icon-ban-circle icon-white"></i>
					<span>{%=locale.fileupload.cancel%}</span>
				</button>
			{% } %}</td>
		</tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
			{% if (file.error) { %}
				<td></td>
				<td class="name" colspan="2"><span>{%=file.name%}</span></td>
				<td class="size1"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
			{% } else { %}
				<td class="preview">{% if (file.thumbnail_url) { %}
					<a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
				{% } %}</td>
				<td class="name" colspan="2">
					<a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
				</td>
				<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
				<td colspan="2"></td>
			{% } %}
			<!--<td class="delete">
				<button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
					<i class="icon-trash icon-white"></i>
					<span>{%=locale.fileupload.destroy%}</span>
				</button>
				<input type="checkbox" name="delete" value="1">
			</td>-->
		</tr>
	{% } %}
	</script>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');


Yii::app()->clientScript->registerScript('inlineScript', <<<EOD


$("#sortable" ).sortable()

// image uploads
var imageCount = $('li','#sortable').length
var uploadedImages = []


'use strict';
window.locale = {
	"fileupload": {
		"errors": {
			"maxFileSize": "File is too big",
			"minFileSize": "File is too small",
			"acceptFileTypes": "Filetype not allowed",
			"maxNumberOfFiles": "Max number of files exceeded",
			"uploadedBytes": "Uploaded bytes exceed file size",
			"emptyResult": "Empty file upload result"
		},
		"error": "Error",
		"start": "Start",
		"cancel": "Cancel",
		"destroy": "Delete"
	}
};

// Initialize the jQuery File Upload widget:
// $('#fileupload').fileupload();

//jquery.fileupload-ui.js
$('#fileupload').fileupload({
	completed: function (e, data) {
		$.each(data.result, function (index, file) {

			if(file.error == undefined){
				uploadedImages.push({name:file.name, thumbnail:file.thumbnail_url, url:file.url, type:file.type, size:file.size, width:file.width, height:file.height})
				
				var totalFilesLeftToUpload = $('.template-upload')
				
				//if(totalFilesLeftToUpload.length == 0){ //could click on individual 'start' button...
					$('button.OKButton').removeClass('disabled')
					$('button.OKButton').attr("disabled", false)
				//}
			}
		});
	},
});

//*************************************************************************
// Called when clicked on 'OK" button in the Upload Files Dialog box
//************************************************************************
function addImages(){
	var imagesWrapper = $('.productImages')
	var html = ''
	var size = ''
	
	$.each(uploadedImages , function(i, image) {
		html = ''
		
		size = parseInt(image['size'],10)

		if(size <1024){
			size += 'B'
		}else if(size < 1024*1024){
			size = Math.round((size/1024)*Math.pow(10,1))/Math.pow(10,1) + 'KiB'
		}else{
			size = Math.round((size/(1024*1024))*Math.pow(10,2))/Math.pow(10,2) + 'MB'
		}
		
		html += '<li><img alt="" src="'+image['thumbnail']+'">'
		html += 	'<input id="uploadedImage_'+imageCount+'" type="hidden" value="|NEWIMAGE|'+image['url']+'" name="Product[images][]">'
		html += 	'<div class="overlay"><table><tbody><tr><th>Type</th>'
		html += 		'<td>'+image['type']+'</td>'
		html += 		'</tr><tr><th>Dimensions</th>'
		html += 		'<td>'+image['width']+'&times;'+image['height']+'</td>'
		html += 		'</tr><tr><th>Size</th>'
		html += 		'<td>'+size+'</td>'
		html += 	'</tr></tbody></table></div>'
		html += '</li>'
		
		imagesWrapper.append(html)
		
		imageCount ++
	});	
}

		
//*************************************************************************
// Called when clicked on the Upload Files link (opens the Upload Files Dialog box)
//************************************************************************
function imageUploadInit(){
	var buttons = $('span[class="ui-button-text"]')
	
	uploadedImages = []
	buttons.each(function(index, domEle){
		if($(domEle).text() == 'Ok'){
			$(domEle).parent().addClass('OKButton')
			$(domEle).parent().addClass('disabled')
			$(domEle).parent().attr("disabled", true)
		}
    });
	
	var oldFiles = $('tbody.files')
	
	oldFiles.empty()
}




function generateOptionGroup(id, title)
{
	var html = ''
	var dropList = ''
	
	dropList += '<select class="Product_option_ids" rel="'+id+'" title="'+title+'" name="Product[option_ids_drop]">' //stick option group id in here somewhere
	dropList += '<option selected="selected" value="">** Add Option **</option>'
	
	$.ajax({
		url: '/admin/ProductOption/dropList',
		dataType: 'json',
		data:{id: id},
		type: "POST",
		success: function(data) {
			
			console.log(data, data.dropList.length,dropList);
			
			if(data.status == 'success') {
				$(data.dropList).each(function(index, element) {
					dropList += '<option value="'+element.id+'" price="'+element.price+'" code="'+element.code+'">'+element.title+'</option>'
				});
			}
			dropList += '<option disabled="disabled" value="-">--------------------</option>'
			if(data.dropList.length > 0) dropList += '<option value="all">Add All Options</option>'
			dropList += '<option value="new">Add New Option</option>'
			dropList += '</select>'
			
			html += '<fieldset id="groupId_'+id+'">'
			html += '<input type="hidden" name="Product[optionGroup_ids][]" value="'+id+'">'
			
			html += '<legend>'
			html += 	'<input id="ytoptionGroupActive_'+id+'" type="hidden" value="" name="Product[options]['+id+'][optionGroupSettings][optionGroupActive]">'
			html += 	'<input id="optionGroupActive_'+id+'" type="checkbox" value="1" name="Product[options]['+id+'][optionGroupSettings][optionGroupActive]" checked="checked">'
			html += 	'<span class="optionGroupActive"></span>'+title
			html += '</legend>'
			
			html += '<p><span class="option">Option</span><span class="price">Price</span><span class="code">Product Code Prefix/Sufix</span><span class="sale">On Sale</span></p>'
			html += '<div class="optionsWrapper"></div>'
			html += dropList
			html += '</fieldset>'
			
			$('#optionGroups').append(html)
		},
	});
}

function generateOption(groupId, id, title, price, code)
{
	var html = ''
	
	html += '<div id="groupOption_groupID_'+groupId+'_optionID_'+id+'">'
	html += '<input type="hidden" name="Product[options]['+groupId+']['+id+'][options_new]" value="1">'
	
	html += '<div class="optionColumn option">'
	html += '<input id="ytoption_status_'+id+'" type="hidden" name="Product[options]['+groupId+']['+id+'][options_status]" value="0">'
	html += '<input id="option_status_'+id+'" class="canBeDisabled" type="checkbox" name="Product[options]['+groupId+']['+id+'][options_status]" value="1" checked="checked">'
	html += '<label for="option_status_'+id+'">'+title+'</label>'
	html += '</div>'

	html += '<div class="optionColumn price">'
	html += '<input id="option_price_'+id+'" class="canBeDisabled" type="text" value="" name="Product[options]['+groupId+']['+id+'][options_price]" maxlength="20" size="10">'
	html += '</div>'
	
	html += '<div class="optionColumn code">'
	html += code
	html += '</div>'

	html += '<div class="optionColumn sale">'
	html += '<input id="ytoption_sale_'+id+'" type="hidden" name="Product[options]['+groupId+']['+id+'][options_sale]" value="0">'
	html += '<input id="option_sale_'+id+'" class="canBeDisabled" type="checkbox" name="Product[options]['+groupId+']['+id+'][options_sale]" value="1">'
	html += '</div>'
	
	html += '<div class="clear"></div>'
	html += '</div>'
	
	$('.optionsWrapper','#groupId_'+groupId).append(html)
}

$(document).ready(function()
{
	
	//*************************************************************************
	// Disables Option Groups depending on state of that option groups active checkbox
	//*************************************************************************
	$('input','legend').live('click', function(e) {
		var optionGroup = $(this).parent().parent()

		if(this.checked){
			$('input[class="canBeDisabled"]',optionGroup).prop('disabled', false)
			//$('input[class="canBeDisabled"]',optionGroup).attr('readonly','readonly')
		}else{
			$('input[class="canBeDisabled"]',optionGroup).prop('disabled', true)
			//$('input[class="canBeDisabled"]',optionGroup).removeAttr('readonly')
		}
		$(this).prop('disabled', false)
	});
	

	$('fieldset','#optionGroups').each(function(index) {
		var globalActive = $('legend input',$(this))
		
		if(globalActive.is(':checked') == false){
			$('input[class="canBeDisabled"]',$(this)).prop('disabled', true)
		}
		
    });
    
    //Turn back on all disabled fiedls so get passed in POST (Disabled fields do no get POSTED!!!)
    $('#product-form').submit(function(e){
    	$('input[class="canBeDisabled"]').prop('disabled', false)
    	//e.preventDefault();
    });
    
    
	//*************************************************************************
	// Hides/shows correct options depending on state of Global Price checkbox
	//*************************************************************************
	$('#Product_global_price').bind('click', function(e) {
		var priceSaleDiv = $('.priceSale')
		var optionsDiv = $('.options')
		if(this.checked){
			priceSaleDiv.slideDown('slow');
			optionsDiv.slideUp(500)
		}else{
			priceSaleDiv.slideUp(500)
			optionsDiv.slideDown('slow');
		}
		//e.preventDefault();
	});
	
	if($('#Product_global_price').is(":checked") == false){
		$('.priceSale').hide();
	}else{
		$('.options').hide();
	}
	
	//*************************************************************************
	// Displays 'Add New Option Group' dialog if Add is selected in Option Group droplist 
	//*************************************************************************
	$('#Product_optionGroup_ids_drop').change(function()
	{
		switch($(this).val())
		{
			case 'new':
				$(this).val("")
				$("#ProductOptionGroup_title").val("")
				$("#AddNewOptionGroup").dialog("open");
			break
			
			case '':
				//do nothing
			break
			
			default:
				selected = $('option:selected', this)
				//$("#selectBox").append('<option value="option5">option5</option>');

				generateOptionGroup($(this).val(), selected.html())
			
				selected.remove();
			break
		
		}
	});
	
	$("#Product_optionGroup_ids_drop option[value='-']").attr('disabled', 'disabled');
	
	//*************************************************************************
	// Displays 'Add New Option' dialog if Add is selected in Group droplist 
	//*************************************************************************
	$('.Product_option_ids').live("change", function()
	{
		switch($(this).val())
		{
			case 'new':
				$(this).val("")
			
				$('#ui-dialog-title-AddNewOption').html('Add New Option - '+$(this).attr('title'))
				$('#ProductOption_group_id').val($(this).attr('rel'))
				
				$("#ProductOption_title").val("")
				$("#ProductOption_global_price").val("")
				$("#ProductOption_global_code").val("")
				
				$("#AddNewOption").dialog("open");
			break
			
			case 'all':
				options = $('option[price]',this)
				groupId = $(this).attr('rel')
				options.each(function(index, element) {
					
					generateOption(groupId,$(element).val(), $(element).html(),$(element).attr('price'),$(element).attr('code'))
					$(element).remove();
				});
				$("option[value='all']", this).remove();
			break
			
			case '':
				//do nothing
			break
			
			default:
				selected = $('option:selected', this)

			
				generateOption($(this).attr('rel'),$(this).val(), selected.html(),selected.attr('price'),selected.attr('code'))
				
				selected.remove();
				
				//remove Add All Options if none left in list...
				if($('option',this).length == 4){
					$("option[value='all']", this).remove();
				}
			break
		}
	});
});

EOD
	,CClientScript::POS_END);
?>