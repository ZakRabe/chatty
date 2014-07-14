<?php

class UploadController extends Controller
{
	
	private $_model;

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
			//this stopped uploading of images for some reason!!
			//array('deny',  // deny all users
				//'users'=>array('*'),
			//),
		);
	}
	
	public function actionIndex()
	{
        $model=new Upload;
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionCreate()
    {
        $model=new Upload;
        $data=array();
        if(isset($_POST['Upload']))
        {
			$images_url = Yii::app()->assetManager->baseUrl . "/" . $model->images_dir . "/";
            $model->attributes=$_POST['Upload'];
            $model->image=CUploadedFile::getInstance($model,'image');
            if($model->save())
				$data['url'] = $images_url.$model->image->name;
            else
				$data['error'] = 'ERROR: File was not uploaded';
        }
		echo CJavaScript::jsonEncode($data);
		Yii::app()->end();
    }
    
    
	
	public function actionFind() 
	{
		$model=new Upload;
		$total = 0;
		$output = array();
		$output['images'] = array();
		$images_path = Yii::app()->assetManager->basePath . "/" . $model->images_dir . "/";
		$cache_path = Yii::app()->assetManager->basePath . "/" . $model->cache_dir . "/";
		$images_url = Yii::app()->assetManager->baseUrl . "/" . $model->images_dir . "/";
		$cache_url = Yii::app()->assetManager->baseUrl . "/" . $model->cache_dir . "/";
		$search = empty($_GET['search']) ? false : $_GET['search'];
		$start = empty($_GET['resultstart']) ? 1 : $_GET['resultstart'];
		$end = 0;
		
		// check upload path
		if (!is_dir($images_path))
			$output['error'] = "Error: Image directory doesn't exist";

		// process
		else {
			$files = array();
			if ($handle = opendir($images_path)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if ($search && stripos($file, $search) === false)
							continue;
						$files[] = $file;
					}
				}
				closedir($handle);
			}
			sort($files);
			foreach ($files as $file) {
				$total++;
				if ($total >= $start && count($output['images']) < $model->find_limit) {
					$imagepath = $images_path.$file;
					if ($imgsize = @getimagesize($imagepath)) {
						$extension = explode(".", $imagepath);
						$extension = end($extension);
						$thumbhash = md5($model->thumbnail_width.$model->thumbnail_height.'inside'.$imagepath);
						$thumbname = $thumbhash.'.'.$extension;
						$thumbpath = $cache_path.$thumbname;
						if (!file_exists($thumbpath)) {
							$image = WideImage::load($imagepath);
							$resized = $image->resize($model->thumbnail_width,$model->thumbnail_height,'inside');
							$resized->saveToFile($thumbpath);
						}
						$thumb = CHtml::image($cache_url.$thumbname);
						$output['images'][] = array(
							'filename' => "<a href=\"".$images_url.$file."\">".$file."</a>",
							'filesize' => $model->byte_convert(filesize($images_path.$file)),
							'thumbnail' => $thumb,
							'dimensions' => $imgsize[0]."&times;".$imgsize[1],
							'filetype' => $imgsize['mime']
						);
					}else {
						$output['images'][] = array(
							'filename' => $file,
							'filesize' => $model->byte_convert(filesize($images_path.$file)),
							'thumbnail' => "&nbsp;",
							'dimensions' => "&nbsp;",
							'filetype' => "&nbsp;"
						);
					}
					$end++;
				}
			}
		}

		$output['total'] = $total;
		$output['start'] = $start;
		$output['end'] = $end;
		
		echo CJavaScript::jsonEncode($output);
		Yii::app()->end();
	}
	
}
