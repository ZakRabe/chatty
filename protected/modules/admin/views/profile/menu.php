<ul class="actions">
<?php 
if(AdminModule::isAdmin()) {
?>
<li><?php echo CHtml::link(AdminModule::t('Manage User'),array('/user/admin')); ?></li>
<?php 
} else {
?>
<li><?php echo CHtml::link(AdminModule::t('List User'),array('/user')); ?></li>
<?php
}
?>
<li><?php echo CHtml::link(AdminModule::t('Profile'),array('/user/profile')); ?></li>
<li><?php echo CHtml::link(AdminModule::t('Edit'),array('edit')); ?></li>
<li><?php echo CHtml::link(AdminModule::t('Change password'),array('changepassword')); ?></li>
<li><?php echo CHtml::link(AdminModule::t('Logout'),array('/user/logout')); ?></li>
</ul>