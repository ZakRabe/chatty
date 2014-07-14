<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('group_id')); ?>:</b>
	<?php echo CHtml::encode($data->group_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_price')); ?>:</b>
	<?php echo CHtml::encode($data->global_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('global_code')); ?>:</b>
	<?php echo CHtml::encode($data->global_code); ?>
	<br />


</div>