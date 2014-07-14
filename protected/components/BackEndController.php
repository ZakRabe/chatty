<?php
class BackEndController extends Controller
{
	public function init() 
	{
		
		Yii::app()->setComponents(array(
			'user'=>array(
				'class'=>'CWebUser',
				'loginUrl'=>Yii::app()->createUrl('admin/login')
			),
			'errorHandler'=>array(
				// use 'site/error' action to display errors
				'errorAction'=>'/admin/default/error',
			),
			'widgetFactory'=>array(
				'class'=>'CWidgetFactory',
				'widgets'=>array(
					'CGridView'=>array(
						'cssFile'=>false,
					),
					'CDetailView'=>array(
						'cssFile'=>false,
					),
					'CJuiAutoComplete' => array(
						'themeUrl' => '/themes/reactor/css',
						'theme' => 'jui',
					),
					'CJuiDialog' => array(
						'themeUrl' => '/themes/reactor/css',
						'theme' => 'jui',
					),
					'CJuiDatePicker' => array(
						'themeUrl' => '/themes/reactor/css',
						'theme' => 'jui',
					),
				)
			),
		));
		
		Yii::app()->user->setStateKeyPrefix('backend_');
		
	}
}
