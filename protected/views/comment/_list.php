<?php

$isLeft = true;
$colorId = 1;
foreach ($comments as $key => $value) {
	$text = $value->attributes['text'];
	$name = $value->attributes['name'];
	$city = $value->attributes['city'];
	if($isLeft){
		$columnClass = "left";
		$isLeft = false;
	}else{
		$columnClass = "right";
		$isLeft = true;
	}
	switch ($colorId) {
		case 1:
		$colorClass = '';
		$colorId++;
		break;
		case 2:
		$colorClass = 'alt';
		$colorId++;
		break;
		case 3:
		$colorClass = 'alt';
		$colorId++;
		break;
		case 4:
		$colorClass = '';
		$colorId = 1;
		break;

	}


	echo '<div class="speech posted ' . $columnClass . " " . $colorClass .  '"><p class="comment posted">';
	echo $text . '</p>';
	echo '<hr><span class="identity posted"><b>' . $name . '</b>, <i>' . $city . '</i></span></div>';
	
}