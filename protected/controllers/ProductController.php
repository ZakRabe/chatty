<?php

class ProductController extends Controller
{
	public $selectedCategoryId;
	
	
	public function actionIndex(){
		//redirect
		$this->render('index');
	}
	
	
	public function actionView($id){
		//redirect
		$this->render('index');
	}

	
	
	public function actionCategory($id){
		$model = ProductCategory::model()->findByPk($id);

		if(empty($model) || $model->parent_id != 0){
			$this->redirect('/');
		}
		
		$categories = ProductCategory::model()->findAllByAttributes(array('parent_id' => $model->id), array('order' => 'sort'));
		
		switch($model->id){
			case 1:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 2:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 3:
				$banner = '/img/bannerPureSouth.jpg';
			break;
		}
		
		$this->render('category',array(
			'model'=>$model,
			'categories'=>$categories,
			'banner'=>$banner
		));
	}
	
	public function actionList($id){
		$model = ProductCategory::model()->findByPk($id);

		//make sure id valid and that products can be attached to this level of Product Catagory
		if(empty($model) || !in_array(ProductCategory::model()->getLevel($id), Yii::app()->params['productAtCategoryLevels']) || $model->status==0){
			$this->redirect('/');
		}
		

		if($model->parent_id == 0){
			$parent = $model;
		}else{
			$parent = ProductCategory::model()->findByPk($model->parent_id );
		}
		
		$pageNum = 0;
		if(isset($_GET['page'])){
			$pageNum = $_GET['page'];
			if(Yii::app()->request->isAjaxRequest){
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
				
				$this->layout='//layouts/blank';
			}
		}
		
		switch($model->parent_id){
			case 1:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 2:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 3:
				$banner = '/img/bannerPureSouth.jpg';
			break;
		}
		
		
		$this->selectedCategoryId = $id;
		
		$this->render('list',array(
			'model'=>$model,
			'parent'=>$parent,
			'banner'=>$banner,
			'pageNum'=>$pageNum
		));
		
	}
	
	
	
	public function actionDetail($categoryId,$productId){
		$model = Product::model()->allOptionsDetail()->findByPK($productId);

		if(empty($model)){
			$this->redirect('/');
		}
		
		
		$categoryList = array();
		$bannerId = 0;
		$links = array('/product/category/','/product/list/');
		
		if($categoryId != 0){
			$category = ProductCategory::model()->findByPK($categoryId);
		}else{
			//can belong to more than one...!!! so which one do I use for the breadcrumbs and the banner!! Hopefully product won't cross over to another main category
			$categoryId = $model->productCategories[0]->id;
			$category = ProductCategory::model()->findByAttributes(array('id' => $categoryId));
		}
		
		
		
		$level = ProductCategory::model()->getLevel($categoryId);
		$categoryList[] = array('title' => $category->title, 'id' => $category->id, 'link' => $links[$level]);
		if($level == 0) $bannerId = $category->id;
		if($category->status == 0) $this->redirect('/');
		
		while($category->parent_id != 0){
			$category = ProductCategory::model()->findByPK($category->parent_id);
			$level = ProductCategory::model()->getLevel($category->id);
			$categoryList[] = array('title' => $category->title, 'id' => $category->id, 'link' => $links[$level]);
			if($level == 0) $bannerId = $category->id;
			if($category->status == 0) $this->redirect('/');
		}
		
		$categoryList = array_reverse($categoryList);
			
		switch($bannerId){
			case 1:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 2:
				$banner = '/img/bannerPureSouth.jpg';
			break;
			
			case 3:
				$banner = '/img/bannerPureSouth.jpg';
			break;
		}
		
		$this->selectedCategoryId = $categoryId;
		
		$this->render('detail',array(
			'model'=>$model,
			'categoryList'=>$categoryList,
			'banner'=>$banner,
			'globalPriceOptionId' => ProductOption::model()->getGlobalPriceOptionId()
		));

	}
	
	
	
	public function actionAddCart(){
		
		if( Yii::app()->request->isAjaxRequest ){ //From Create Product page
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			
			//$this->performAjaxValidation($model);
			
			$globalPriceOptionGroupID = ProductOptionGroup::model()->getGlobalPriceOptionGroupId();
			$globalPriceOptionID = ProductOption::model()->getGlobalPriceOptionId();
			
				
			$session=new CHttpSession;
  			$session->open();
			$cart = Cart::model()->findByAttributes(array('sess_id' => $session->getSessionID()));
			
			
			
			if(isset($_POST['Quantity'])){
				
				$op = '';

				foreach($_POST['Quantity'] as $productId => $optionGroups){
					
					$product = Product::model()->allOptionsDetail()->findByPK($productId);
					
					if($product){ //make sure valid product and it's status is active etc 
					
						foreach($optionGroups as $optionGroupId => $options){
							foreach($options as $optionId => $quantity){
								
								if($quantity > 0){
									
									if(!$cart){ //create cart if doesn't exists
										$cart = Cart::create($session->getSessionID(), $userID = 0); //if logged in then link to UserID!!
									}

									$productOption = $this->findOption($product->productOptionLinks, $optionGroupId, $optionId);
									
									if($productOption){ //valid product optionGroupID and optionID
										
										if($optionGroupId == $globalPriceOptionGroupID && $optionId == $globalPriceOptionID){
											
											//global price
											$cartProduct = CartProduct::model()->findByAttributes(array('cart_id' => $cart->getPrimaryKey(), 'product_id' => $productId));
											if($cartProduct){
												$cartProduct->quantity = $cartProduct->quantity + $quantity;
												$cartProduct->save();
											}else{
												CartProduct::create($cart->getPrimaryKey(), $productId, $productOption->price->id, $quantity);
											}
										}else{
											
											//a product option
											$cartProducts = CartProduct::model()->findAllByAttributes(array('cart_id' => $cart->getPrimaryKey(), 'product_id' => $productId));
											if(!$cartProducts){ //only if none
												$cartProduct = CartProduct::create($cart->getPrimaryKey(), $productId, $productOption->price->id, $quantity);
												CartProductOption::create($cartProduct->getPrimaryKey(), $optionId);
											}else{
												//could have cartProducts but not one with correct option!!
												
												$isFound = false;
												foreach($cartProducts as $cartProduct){
													$cartProductOption = CartProductOption::model()->findByAttributes(array('cart_product_id' => $cartProduct->getPrimaryKey(), 'option_id' => $optionId));
													if($cartProductOption){
														//found correct one	
														$cartProductOption->quantity = $cartProductOption->quantity + $quantity;
														$cartProductOption->save();
														$isFound = true;
													}
												}
												
												if($isFound == false){ 
													//ie have cart products but not one with this option!!
													$cartProduct = CartProduct::create($cart->getPrimaryKey(), $productId, $productOption->price->id, $quantity);
													CartProductOption::create($cartProduct->getPrimaryKey(), $optionId);	
												}
											}
											

										}

										$op .= 	$productId.' '.$optionGroupId.' '.$optionId.' '.$quantity.' '.$cart->getPrimaryKey().' | ' ;
									}
								}
								
							}
						}
					}
				}
				
				//if group global price then need to handle ie no priceID cause no row in product_price table!!!!  
				
				echo CJSON::encode( array(
					'status' => 'success',
					'debug' => $op,
				));
					
			}else{
				echo CJSON::encode( array('status' => 'failure'));	
			}
			exit;
		}else{
			$this->redirect('/');
		}

	}
	
	public function findOption($optionLinks, $optionGroupID, $optionID){
		foreach($optionLinks as $option){
			if($option->option->group->id == $optionGroupID && $option->option->id == $optionID){
				return 	$option;
			}
		}
		
		return false;
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}