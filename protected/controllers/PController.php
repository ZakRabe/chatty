<?php

class PController extends FrontEndController
{

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$html = $model->content;
		preg_match_all("|<p class=\"embed-html\">(.*)</p>|U", $html, $out);
		foreach ($out[1] as $key => $src)
			$html = str_replace("<p class=\"embed-html\">".$src."</p>", html_entity_decode($src), $html);
		$this->render('view',array(
			'model'=>$model,
			'html'=>$html,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionModal($id)
	{
		$this->layout = 'modal';
		$model = $this->loadModel($id);
		$html = $model->content;
		preg_match_all("|<p class=\"embed-html\">(.*)</p>|U", $html, $out);
		foreach ($out[1] as $key => $src)
			$html = str_replace("<p class=\"embed-html\">".$src."</p>", html_entity_decode($src), $html);
		$this->render('modal',array(
			'model'=>$model,
			'html'=>$html,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CmsPage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
}
