<div class="summary">Select <?php echo isset($route)?'':'parent';?> category:</div>
<nav id="cat-list">
	<ul>
		<?php if(Yii::app()->controller->id == 'product'): ?>
		<li class="<?php echo !$parent?'active':''; ?>"><a href="<?php echo $this->createUrl('/admin/'.$route); ?>">[show all]</a></li>
		<?php ;else: ?>
		<li class="<?php echo !$parent?'active':''; ?>"><a href="<?php echo $this->createUrl('/admin/'.(isset($route)?$route:'productCategory/')); ?>">[top]</a></li>
		<?php endif; ?>
<?php
$theList = isset($route)?$list:$list['catList'];

foreach($list['catList'] as $id => $title)
{
	echo "<li";
	if ($parent && $id == $parent->id)
		echo " class=\"active\"";
		
	if(isset($route)){
		if(in_array($list['optionsList'][$id]['level'], Yii::app()->params['productAtCategoryLevels'])){ 
			echo "><a href=\"".$this->createUrl((isset($route)?$route:'productCategory/').$id)."\">".$title."</a></li>\n";
		}else{
			echo ">".$title."</li>\n";
		}
	}else{
		if($list['optionsList'][$id]['level'] < Yii::app()->params['maxCategoryLevels']){
			echo "><a href=\"".$this->createUrl((isset($route)?$route:'productCategory/').$id)."\">".$title."</a></li>\n";
		}else{
			echo ">".$title."</li>\n";
		}
	}
	
	
}
?>
	</ul>
</nav>
