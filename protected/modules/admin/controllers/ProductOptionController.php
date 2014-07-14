<?php

class ProductOptionController extends BackEndController
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
		
		$model=new ProductOption;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);


		if( Yii::app()->request->isAjaxRequest ){ //From Create Product page
		
			// Stop jQuery from re-initialization
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			
			$this->performAjaxValidation($model);
			
			if(isset($_POST['ProductOption'])){
				$model->attributes=$_POST['ProductOption'];
				
				if($model->save()){
					//set order to id
					$id = $model->getPrimaryKey();
					$model->sort = $id;
					$model->save();
				
					echo CJSON::encode( array(
						'status' => 'success',
						'group_id' => $model->group_id,
						'id' => $model->getPrimaryKey(),
						'title' => $model->title,
						'global_price' => $model->global_price,
						'global_code' => $model->global_code,
						'content' => $this->renderPartial( '_formPopup', array('model' => $model ), true, true ),
					));
				}else{
					echo CJSON::encode( array(
						'status' => 'failure',
						'content' => $this->renderPartial( '_formPopup', array('model' => $model ), true, true ),
					));
				}
					
			}
			exit;
			
		}else{
			
			if(isset($_POST['ProductOption'])){
				
				$model->attributes=$_POST['ProductOption'];
				
				if(isset($_GET['optionGroup_id'])){
					$model->group_id = $_GET['optionGroup_id'];
				}else{
					$model->group_id = 0;
				}
		
				if($model->save()){
				
					//set order to id
					$id = $model->getPrimaryKey();
					$model->sort = $id;
					$model->save();
					
					if($model->group_id == 0){
						$this->redirect(array('index'));
					}else{
						$this->redirect($model->group_id);
					}
				
					//$this->redirect(array('view','id'=>$model->id));
				}	
			}
		
			$this->render('create',array(
				'model'=>$model,
			));
		}

	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductOption']))
		{
			$model->attributes=$_POST['ProductOption'];
			if($model->save()){	
				$this->redirect(array('view','id'=>$model->id));
			}
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
		$model = new ProductOption('search');
		
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['ProductOption']))
			$model->attributes=$_GET['ProductOption'];
		
		if(isset($_GET['id'])){
			$model->group_id = $_GET['id'];
			$optionGroup = ProductOptionGroup::model()->findByPK($model->group_id)->title;
		}else{
			$model->group_id = 0;
			$optionGroup = '';
		}
		
		$this->render('index',array(
			'model'=>$model,
			'optionGroup' => $optionGroup
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ProductOption('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductOption']))
			$model->attributes=$_GET['ProductOption'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	
	public function actionOrder()
	{

		$response = array('success' => false);
		
		
		for($i=1; $i<=count($_POST['newOrder']); $i++){
			$model=ProductOption::model()->findByPK($_POST['newOrder'][$i-1]);
			$model->sort = $i;
			$model->save();
		}
		
		$response = array('success' => true);
		
		echo json_encode($response); 
	
	}
	
	public function actionDropList(){
		
		if( Yii::app()->request->isAjaxRequest ){ //From Create Product page
		
			// Stop jQuery from re-initialization
			//Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			
			if(isset($_POST['id'])){
				echo CJSON::encode( array(
					'status' => 'success',
					'test' => array(1,2,3),
					'dropList' => ProductOption::model()->getDropList($_POST['id'],'ajax'),
				));
			}
			exit;
			
		}
		echo CJSON::encode( array(
			'status' => 'failure',
		));
	}
	
	
	
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ProductOption::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-option-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
