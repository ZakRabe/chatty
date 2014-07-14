<?php

class SiteController extends FrontEndController
{
	public $selectedCategoryId;
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//$model=new CmsPage();
		
		//$model = CmsPage::model()->findByAttributes(array('id' => array(1,2,3)));

		//$categories = ProductCategory::model()->getCategoriesList();
		
		
		/*
		$products = Product::model()->with('productOptions','productPrices:latest', 'productImages:gridImage')->findAllByAttributes(array('feature' => 1, 'status' => 1));
		
		foreach($products as $product){
			op($product->title);
			foreach($product->productImages as $image){
				op($image->title);
				$image->sort = 3333;
			}
			$image->save(); //WORKS!!
			foreach($product->productPrices as $price){
				op($price->price . ' '. $price->sale. ' '. $price->option_id);
			}
			foreach($product->productOptions as $category){
				op($category->title, $category->group->title);
			}
			//$product->save(); //doesn't update related model
		}
		*/
		
		
		//op(count($model));
		//$model = Product::model()->findByAttributes(array('id' => array(1,2,3)));
		
		$model = new Product('searchActive');
		$model->unsetAttributes();
		
		//For sorting br price hi->lo etc
		/*
		if (isset($_GET['Contact']))
        	$model->attributes = $_GET['Contact']; 
        */
        
        //$model->globalPriceOptionId = ProductOption::model()->getGlobalPriceOptionId();
        
        $this->selectedCategoryId = 0;

		$this->render('index',array(
			'model'=>$model,
			'banner'=>'/img/bannerBotany.jpg',
			//'categories'=>$categories,
		));

	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	elseif ($error['type'] == 'PHP Error')
	        	$this->render('error_php', $error);
	        else
	        	$this->render('error', $error);
	        
	        // email support
	        if ($error['type'] != 'CHttpException') 
	        {
				$message = new YiiMailMessage;
				$message->view = 'error';
				$message->subject = Yii::app()->params['emailSubjectPrefix'].' '.$error['type'].' ('.$error['code'].')';
				$message->setBody(array('error'=>$error), 'text/html');
				$message->addTo(Yii::app()->params['supportEmail']);
				$message->from = Yii::app()->params['fromEmail'];
				Yii::app()->mail->send($message);
			}
	    }
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
