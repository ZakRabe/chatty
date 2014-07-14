<?php
	$extra = '';

?>
<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Product Option Groups'),array('/admin/productOptionGroup')); ?></li>
	<li><?php echo CHtml::link(AdminModule::t('Create Product Option Group'),array('/admin/productOptionGroup/create')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->