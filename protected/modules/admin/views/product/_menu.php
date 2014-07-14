<?php
	$extra = '';
	if(isset($_GET['id'])) $extra = '?category_id='.$_GET['id'];
?>
<ul class="submenu">
	<li><?php echo CHtml::link(AdminModule::t('Manage Products'),array('/admin/product')); ?></li>
	<li><?php echo CHtml::link(AdminModule::t('Create Product'),array('/admin/product/create'.$extra),array('class'=>'createProduct')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->