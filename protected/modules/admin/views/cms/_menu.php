<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Pages'),array('/admin/cms')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->