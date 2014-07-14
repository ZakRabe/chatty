<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Admins'),array('/admin/admin')); ?></li>
	<li><?php echo CHtml::link(AdminModule::t('Create Admin'),array('/admin/admin/create')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->
