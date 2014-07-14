<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->username,
);

echo $this->renderPartial('_menu');

echo $this->renderPartial('_actions', array(
		'list'=> array(
			CHtml::link('Update Details',array('update','id'=>$model->id),array('class'=>'update')),
		),
	));
	
Yii::app()->clientScript->registerScript('search', "

$('form#booking-form').submit(function(){
	if (selectedSessions.length == 0) 
	{
		$('#errorSummary').html('<p>You haven\'t selected any sessions to book!</p>');
		$('#errorSummary').dialog('open');
		return false;
	}
	for (x in selectedSessions)
		$('form#booking-form').append('<input type=\"hidden\" name=\"Bookings[]\" value=\"'+selectedSessions[x]+'\">');
});

$('.column a.create').live('click',function()
{
	var link = this;
	var modalID = $(link).attr('href');
	if (modalID=='#addPaymentModal')
		$(modalID+' #LabPayment_type').attr('value', ($(this).hasClass('coaching')?'coaching_monthly':'subscription_monthly'));
	$(modalID).dialog('option', 'position', {my: 'left bottom', at: 'right', of: link});
	$(modalID).dialog('open');
	return false;
});

");
?>




<h1>View Administrator <strong><?php echo ucwords($model->username); ?></strong></h1>

<div class="column span-12">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			//'id',
			'username',
			'email',
			array(
				'name'=>'status',
				'value'=>$model->status==1?'Active':'Not active',
			),
			array(
				'name'=>'createtime',
				'type'=>'datetime',
			),
		),
	)); ?>
</div>
