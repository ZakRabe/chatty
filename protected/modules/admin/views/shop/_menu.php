<ul class="submenu">
	<!--<li>Orders</li>-->
	<li class="<?php echo Yii::app()->controller->id!='productCategory'?'':'active'; ?>"><?php echo CHtml::link('Categories',array('/admin/productCategory')); ?></li>
	<li class="<?php echo Yii::app()->controller->id!='product'?'':'active'; ?>"><?php echo CHtml::link('Products',array('/admin/product')); ?></li>
	<li class="<?php echo Yii::app()->controller->id!='productOptionGroup'?'':'active'; ?>"><?php echo CHtml::link('Option Groups',array('/admin/productOptionGroup')); ?></li>
	<li class="<?php echo Yii::app()->controller->id!='productOption'?'':'active'; ?>"><?php echo CHtml::link('Options',array('/admin/productOption')); ?></li>
<?php 
	if (isset($list)) {
		foreach ($list as $item)
			echo "<li>".$item."</li>";
	}
?>
</ul><!-- submenu -->