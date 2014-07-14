<?php

class ProductCategoryController extends BackEndController
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
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ProductCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes=$_POST['ProductCategory'];
			$model->image = CUploadedFile::getInstance($model,'image');
			
			if($model->save()){
				if(!empty($model->image->name)) $model->image->saveAs(Yii::app()->assetManager->basePath. '/uploads/images/' . $model->image->name);
			
				//$this->redirect(array('view','id'=>$model->id));
				
				//set order to id
				$id = $model->getPrimaryKey();
				$model->sort = $id;
				$model->save();
				
				if($model->parent_id == 0){
					$this->redirect(array('index'));
				}else{
					$this->redirect($model->parent_id);
				}
				
			}
		
		}
		
		if(isset($_GET['parent_id'])){
			$model->parent_id = $_GET['parent_id'];
			$parent = $this->loadModel($model->parent_id)->title;
		}else{
			$model->parent_id = 0;
			$parent = '';
		}
		
		$this->render('create',array(
			'model'=>$model,
			'parent' => $parent
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$currentImage = $model->image;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes=$_POST['ProductCategory'];
			
			$model->image = CUploadedFile::getInstance($model,'image');
			if(empty($model->image->name)) $model->image = $currentImage;
			
			if($model->save())
				if(!empty($model->image->name)) $model->image->saveAs(Yii::app()->assetManager->basePath. '/uploads/images/' . $model->image->name);
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$model = new ProductCategory('search');
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductCategory']))
			$model->attributes=$_GET['ProductCategory'];
		
		
		if(isset($_GET['id'])){
			$model->parent_id = $_GET['id'];
			$level = $model->getLevel($_GET['id'])+1;
		}else{
			$model->parent_id = 0;
			$level = 0;
		}
		
		$parent = ProductCategory::model()->findByPk($model->parent_id);
		
		// retrieves all Product Categories for dropdown lists + defines options for each ie if disables etc
		$lists = ProductCategory::model()->getCategoriesList();

		$this->render('index',array(
			'model' => $model,
			'level' => $level,
			'parent' => $parent,
			'lists' => $lists,
		));
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ProductCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductCategory']))
			$model->attributes=$_GET['ProductCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
		
	}
	
	
	public function actionOrder()
	{

		$response = array('success' => false);
		
		
		for($i=1; $i<=count($_POST['newOrder']); $i++){
			$model=ProductCategory::model()->findByPK($_POST['newOrder'][$i-1]);
			$model->sort = $i;
			$model->save();
		}
		
		$response = array('success' => true);
		
		echo json_encode($response); 
	
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ProductCategory::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
