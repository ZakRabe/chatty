<?php
	$extra = '';
	if(isset($_GET['id'])) $extra = '?parent_id='.$_GET['id'];
	
	if($parent != '') $parent = ' ('.$parent.')';
?>
<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Product Categories'),array('/admin/productCategory')); ?></li>
	<li><?php echo CHtml::link(AdminModule::t('Create Product Category'.$parent),array('/admin/productCategory/create'.$extra)); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->