<?php

class ProductController extends BackEndController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>AdminModule::getAdmins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		
		$product = Product::model()->with('productPrices:latest')->findByPK($id); //what if save price - need to go back to get pre sale price if in front end!
		$globalOptionId = ProductOption::model()->getGlobalPriceOptionId();
		
		
		$this->render('view',array(
			'model'=>$product,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Product;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		
		$model->optionGroup_ids = array();
		if(isset($_POST['Product']['optionGroup_ids'])) $model->optionGroup_ids = $_POST['Product']['optionGroup_ids'];
		
		$model->options = array();
		if(isset($_POST['Product']['options'])) $model->options = $_POST['Product']['options'];
		
		
		if(isset($_POST['Product']))
		{

			$model->attributes = $_POST['Product'];
			
			if($model->global_price == 1){
				$model->scenario = 'globalPriceCreate';
			}else{
				$model->scenario = 'notGlobalPriceCreate';
			}
			
			//Does the Many_Many save in the link table
			if(isset($_POST['Product']['category_ids'])) $model->productCategories =  $_POST['Product']['category_ids'];
			
			if($model->save()){
				
				$id = $model->getPrimaryKey();
				$model->sort = $id;
				$model->save();
				
				//Save images
				ProductImage::updateImages($_POST['Product']['images'], $model->getPrimaryKey());

				//Save pricing information
				if($model->global_price == 1){
					$productOptionId = ProductOption::model()->getGlobalPriceOptionId();
					$productOptionSettingId = ProductOptionSettings::model()->getGlobalProductOptionSettingId();
					$priceId = ProductPrice::model()->savePrice($model->getPrimaryKey(), $productOptionId, $model->price, $model->isSale, 'id');
					ProductOptionLink::model()->saveLink($model->getPrimaryKey(), $productOptionId, $priceId, $productOptionSettingId);
				}else{
				
					foreach($model->options as $optionGroupId => $optionGroup){
				 		foreach($optionGroup as $optionId => $option){
				 			$priceId = ProductPrice::model()->savePrice($model->getPrimaryKey(), $optionId, $option['options_price'], $option['options_sale'],'id');
				 			$settingsId = ProductOptionSettings::model()->saveSetting($option['options_status'],1, 0, '', 0, 0, 'id');
				 			ProductOptionLink::model()->saveLink($model->getPrimaryKey(), $optionId, $priceId, $settingsId);
				 		}
				 	}	
				}
				
				$this->redirect(array('view','id'=>$model->id));

			}
				
		}else{
			$model->global_price = 0;
			
		}
		
		
		$productOptions = $model->productOptions;
		
		//for drop down list 
		$lists = ProductCategory::model()->getCategoriesList(Yii::app()->params['categoryDisplayMode']);
		
		
		//defines the default ProductCategories the product gets saved to if product category defined in the url
		$model->category_ids = array();
		if(isset($_GET['category_id']) && !isset($_POST['Product'])){
			$model->category_ids = array($_GET['category_id']);
		}
				
		if(isset($_POST['Product']['category_ids'])) $model->category_ids = $_POST['Product']['category_ids'];
		
		
		
		$this->render('create',array(
			'model'=>$model,
			'categoriesList'=>$lists['catList'],
			'optionsList'=> $lists['optionsList'],
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = Product::model()->allOptions()->findByPK($id);
		
		$model->optionGroup_ids = array();
		if(isset($_POST['Product']['optionGroup_ids'])) 
			$model->optionGroup_ids = $_POST['Product']['optionGroup_ids'];
		
		$model->options = array();
		if(isset($_POST['Product']['options'])) 
			$model->options = $_POST['Product']['options'];
		
		$model->images = array();
		if(isset($_POST['Product']['images'])) 
			$model->images = $_POST['Product']['images'];
			
		$model->category_ids = array();
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product'])){
		
			$model->attributes = $_POST['Product'];
			
			if($model->global_price == 1){
				$model->scenario = 'globalPriceUpdate';
			}else{
				$model->scenario = 'notGlobalPriceUpdate';
			}
			
			//Does the Many_Many save in the link table
			//needs to be here else $model->productCategories contains actual model info
			if(isset($_POST['Product']['category_ids'])) $model->productCategories =  $_POST['Product']['category_ids'];
			
			if($model->save()){
				
				//Save images
				ProductImage::updateImages($model->images, $id);
				
				//Save pricing information
				if($model->global_price == 1){
				
					//Global Price
					$productOptionId = ProductOption::model()->getGlobalPriceOptionId();
					$productOptionSettingId = ProductOptionSettings::model()->getGlobalProductOptionSettingId();
					$price = ProductPrice::model()->findByAttributes(array('product_id'=>$id, 'option_id'=>$productOptionId), array('order'=>'`date` desc'));
					
					if(empty($price)){
						$priceId = ProductPrice::model()->savePrice($id, $productOptionId, $model->price, $model->isSale, 'id');
						ProductOptionLink::model()->saveLink($id, $productOptionId, $priceId, $productOptionSettingId);
					}else{
						if($model->price != $price->price || $model->isSale != $price->sale){
							$priceId = ProductPrice::model()->savePrice($id, $productOptionId, $model->price, $model->isSale, 'id');
						 	ProductOptionLink::model()->updateLink($id, $productOptionId, $priceId); //link to latest price
						}
					}
				}else{

					//Not Global Price
					foreach($model->options as $optionGroupId => $optionGroup){
				 		foreach($optionGroup as $optionId => $option){
				 		
				 			if($optionId != 'optionGroupSettings'){
				 			
					 			if(isset($option['options_new'])){
						 			$priceId = ProductPrice::model()->savePrice($id, $optionId, $option['options_price'], $option['options_sale'],'id');
						 			$settingsId = ProductOptionSettings::model()->saveSetting($option['options_status'],$optionGroup['optionGroupSettings']['optionGroupActive'], 0, '', 0, 0, 'id');
						 			ProductOptionLink::model()->saveLink($id, $optionId, $priceId ,$settingsId);
					 			}elseif(isset($option['options_delete'])){
						 			
					 			}else{
						 			$price = ProductPrice::model()->findByAttributes(array('product_id'=>$id, 'option_id'=>$optionId), array('order'=>'`date` desc')); //get last - should get it through link table?!?!
						 			
						 			if($option['options_price'] != $price->price || $option['options_sale'] != $price->sale){
							 			$priceId = ProductPrice::model()->savePrice($id, $optionId, $option['options_price'], $option['options_sale'], 'id');
							 			ProductOptionLink::model()->updateLink($id, $optionId, $priceId); //link to latest price
							 		}
							 		
							 		ProductOptionSettings::model()->updateSettings($id, $optionId, $option['options_status'], $optionGroup['optionGroupSettings']['optionGroupActive'], 0, '', 0, 0); 
							 	}
				 			}
				 			
				 			
				 		}
				 	}
				}
				
				$this->redirect(array('view','id'=>$model->id));
			}
			
		}else{
		
			//NOT SAVE/CREATE
			$globalPriceOptionID = ProductOption::model()->getGlobalPriceOptionId();
			
			foreach($model->productOptionLinks as $optionLink){

				if($optionLink->option->id == $globalPriceOptionID){
				
					//Global Price
					$model->price = $optionLink->price->price;
					$model->isSale = $optionLink->price->sale;
				}else{
					
					//No Global Price
					if(!isset($model->options[$optionLink->option->group->id])){ //first time through
						$model->options[$optionLink->option->group->id] = array();
						$model->options[$optionLink->option->group->id]['optionGroupSettings']['optionGroupActive'] = $optionLink->settings->group_status; //need to get from product_option_settings ??
						$model->optionGroup_ids[] = $optionLink->option->group->id;
					}
					
					
					$info = array();
					$info['options_price'] = $optionLink->price->price;
					$info['options_sale'] = $optionLink->price->sale;
					$info['options_status'] = $optionLink->settings->status;
					
					$model->options[$optionLink->option->group->id][$optionLink->option->id] = $info;
				}
			}
			
			//Get all Catergories linked to Product - could use with('productCategoryLinks') !!!
			$categories = ProductCategoryLink::model()->findAllByAttributes(array('product_id'=>$id));
			
			foreach ($categories as $category){
				$model->category_ids[] = $category->category_id;
			}
			
		}
		
		//for drop down list
		$lists = ProductCategory::model()->getCategoriesList(Yii::app()->params['categoryDisplayMode']);
		
		//what if product belongs to > 1 category
		
		if(!empty($model->productImages)){
			$images = ProductImage::getListByModel($model->productImages);
		}
		
		$this->render('update',array(
			'model'=>$model,
			'categoriesList'=>$lists['catList'],
			'optionsList'=> $lists['optionsList']
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */

	public function actionIndex()
	{
		$model = new Product('search');
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];
		
		
		//retrieves all Product Categories for dropdown lists + defines options for each ie if disables etc
		$lists = ProductCategory::model()->getCategoriesList();
		
		
		//not from view category drop list
		if(!isset($model->category_id_filter))
		{
			//from categorys level 1
			if(isset($_GET['id'])){
				$model->category_id_filter = $_GET['id'];
				$lists['optionsList'][$_GET['id']]['selected'] = true;
			}else{
				$model->category_id_filter = 0;
			}
		}
		
		$category = ProductCategory::model()->findByPk($model->category_id_filter);
		
		$this->render('index',array(
			'model'=>$model,
			'category'=> $category,
			'lists' => $lists,
		));
	}
	
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	
	public function actionOrder()
	{

		$response = array('success' => false);
		
		$op = '';
		
		for($i=1; $i<=count($_POST['newOrder']); $i++){
			$command = Yii::app()->db->createCommand();
			$command->update('product', array('sort'=>$i), 'id=:id', array(':id'=>$_POST['newOrder'][$i-1]));
		
			
			//$model=Product::model()->findByPK($_POST['newOrder'][$i-1]);
			//$model1->scenario = 'sort';
			//$model->sort = $i;
			//$model->save()
		}
		
		$response = array('success' => true );
		
		echo json_encode($response); 
	
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
