<?php
/* 
*/
class CommentController extends CController
{
	public function actionCreate(){
		$model = new Comment;
		// var_dump($model->attributes);
		$model->text = $_POST['text'];
		$model->name = $_POST['name'];
		$model->city = $_POST['city'];
		$model->email = $_POST['email'];
		$response = array();
		if ($model->save()) {
			$html = '<p class="comment">' . $model->text . '</p><hr><span class="identity"><b>' . $model->name . '</b>, <i>' . $model->city . '</i></span>';
			array_unshift($response, $html);
			array_unshift($response, true);
		}else{
			//var_dump($model->errors);	
			foreach ($model->errors as $key => $value) {
				array_push($response, $key);				
			}
			array_unshift($response, false);
		}
		echo CJSON::encode($response);
	}

	public function actionList(){
		$criteria = new CDbCriteria;
		$criteria->order = '`id` DESC';
		$comments = Comment::model()->findAll($criteria);
		foreach ($comments as $key => $value) {
			$text = $value->attributes['text'];
			$name = $value->attributes['name'];
			$city = $value->attributes['city'];

		}
		

		// need to sort descending, so we get the newest at the top. 
		// as you scroll down, load more?
		
		// $comments = Comment::model()->findAll();
		// foreach ($comments as $key => $value){
		// $this->renderPartial('_list', $comments);
	}

}
