<?php
	$extra = '';

?>
<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Product Options'),array('/admin/productOption')); ?></li>
	<li><?php echo CHtml::link(AdminModule::t('Create Product Option'),array('/admin/productOptionG/create')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->