<?php
class FrontEndController extends Controller
{
	
	public function init() 
	{
		
		Yii::app()->user->setStateKeyPrefix('frontend_');
		
	}
	
}
