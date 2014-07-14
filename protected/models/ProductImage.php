<?php

/**
 * This is the model class for table "product_image".
 *
 * The followings are the available columns in table 'product_image':
 * @property integer $id
 * @property integer $product_id
 * @property string $image
 * @property string $title
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property Product $product
 */
class ProductImage extends CActiveRecord
{
	
	public $uploadPath;
	public $cachePath;
	public $cacheUrl = '/assets/cache/';
	
	public $_width;
	public $_height;
	public $_type;
	public $_size;
	
	public function init()
	{
		$this->uploadPath = dirname(Yii::app()->request->scriptFile).'/assets/uploads/images/';
		$this->cachePath = dirname(Yii::app()->request->scriptFile).'/assets/cache/';
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, sort', 'numerical', 'integerOnly'=>true),
			array('image, title', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, image, title, sort', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}
	
	/*
	public function defaultScope()
    {
        return array(
           'order'=>'productImages.sort',
        );
    }
	*/
	public function scopes()
    {
        return array(
					 
            'gridImage'=>array(
                'condition'=>"productImages.title='|ProductGrid|'",
            ),

        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'image' => 'Image',
			'title' => 'Title',
			'sort' => 'Sort',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	// Updates the Products images - setting their order or adding new ones 
	public function updateImages($images, $productID){
		$newImageIdentifer = '|NEWIMAGE|';
		$basePath = Yii::app()->assetManager->basePath;
		
		$count = 0;
		foreach($images as $image){

			if(strpos($image,$newImageIdentifer) === false){
				ProductImage::updateImageOrder($productID, $image, $count);
			}else{
				$imageName = substr($image,strrpos($image, '/')+1); 
				$imageName = ProductImage::checkNameExists(Yii::app()->assetManager->basePath. '/uploads/images/', $imageName);
				$image = urldecode(substr($image,strlen($newImageIdentifer)));

				$source = substr($basePath,0,strrpos($basePath, '/')).$image;
				
				if(copy($source, $basePath. '/uploads/images/'.$imageName)){
					ProductImage::saveImage($productID, $imageName, $count);
				}
				
				//clean up
				unlink($source);
				unlink(str_replace('/assets/uploads/temp/files/','/assets/uploads/temp/thumbnails/',$source));
				
			}
			
			$count ++;
		}
	}
	
	
	// Creates a new images 
	public function saveImage($product_id, $image, $sort, $title = '', $return = 'result'){
		$model = new ProductImage();
		
		$model->product_id = $product_id;
		$model->image = $image;
		$model->title = $title;
		$model->sort = $sort;
		
		$model->save();
		$id = $model->getPrimaryKey();
		
		if($return == 'result'){
			return $model->save();
		}
		else{
			$model->save();
			return $id;
		}
	}
	
	// Updates the images order
	public function updateImageOrder($product_id, $image, $sort){
		$model = ProductImage::model()->findByAttributes(array('product_id'=>$product_id, 'image'=>$image));
		$model->sort = $sort;
		return $model->save();
	}
	
	// Generates the list of Product Images
	public function getListByModel($productImages, $mode = 'byTitle')
	{
		$info = array();
		switch($mode)
		{
			case 'byTitle':
				foreach($productImages as $productImage)
				{
					$info[$productImage->title] = array('id' => $productImage->id, 'product_id' => $productImage->product_id, 'image' => $productImage->image, 'sort' => $productImage->sort);
				}
			break;
		}
		return $info;
	}
	
	// Makes sure that uploaded image does not overwrite an existing image
	public function checkNameExists($path, $image)
	{
		$count = 1;
		$ext = substr($image,strrpos($image, '.')+1); 
		$files = glob($path . $image);
		$newName = $image;
		
		if($ext !== false){
			$name = substr($image, 0, -strlen($ext)-1);
		}else{
			$ext = '';
			$name = $image;
		}

		while(!empty($files)){
			$newName = $name.'_'.$count.(($ext != '')?'.'.$ext:'');
			$files = glob($path . $newName);
			$count ++;
		}
		return $newName;
	}
	
	// Generates new cached image file and returns <img> 
	public function output($width,$height,$limit,$alt='',$htmlOptions=array())
	{
		$imagepath = $this->uploadPath.$this->image;
		$extension = end(explode(".", $this->image));
		$newhash = md5($this->tableName().$this->id.$width.$height.$limit.$imagepath);
		$newfile = $newhash.'.'.$extension;
		$newpath = $this->cachePath.$newfile;
		if (!file_exists($newpath)) 
		{
			$wideImage = WideImage::load($imagepath);
			$resized = $wideImage->resize($width,$height,$limit);
			$resized->saveToFile($newpath);
		}
		$newurl = $this->cacheUrl.$newfile;
		return CHtml::image($newurl, $alt, $htmlOptions);
	}
	
	public function getWidth()
	{
		if (!$this->_width)
			$this->_setStats();
		return $this->_width;
	}
	
	public function getHeight()
	{
		if (!$this->_height)
			$this->_setStats();
		return $this->_height;
	}
	
	public function getSize()
	{
		if (!$this->_size)
			$this->_setStats();
		return $this->_size;
	}
	
	public function getType()
	{
		if (!$this->_type)
			$this->_setStats();
		return $this->_type;
	}
	
	public function _setStats()
	{
		if (empty($this->image))
			return false;
			
		$imagepath = $this->uploadPath.$this->image;
		if (!$imagesize = @getimagesize($imagepath))
			die($imagepath);
			
		$this->_size = $this->_format_bytes(filesize($imagepath));
		list($this->_width, $this->_height) = $imagesize;
		$this->_type = image_type_to_mime_type($imagesize[2]);
	}
	
	public function onAfterSave()
	{
		$this->_setStats();
	}
	
	private function _format_bytes($a_bytes)
	{
		if ($a_bytes < 1024) {
			return $a_bytes .' B';
		} elseif ($a_bytes < 1048576) {
			return round($a_bytes / 1024, 2) .' KiB';
		} elseif ($a_bytes < 1073741824) {
			return round($a_bytes / 1048576, 2) . ' MiB';
		} elseif ($a_bytes < 1099511627776) {
			return round($a_bytes / 1073741824, 2) . ' GiB';
		} elseif ($a_bytes < 1125899906842624) {
			return round($a_bytes / 1099511627776, 2) .' TiB';
		} elseif ($a_bytes < 1152921504606846976) {
			return round($a_bytes / 1125899906842624, 2) .' PiB';
		} elseif ($a_bytes < 1180591620717411303424) {
			return round($a_bytes / 1152921504606846976, 2) .' EiB';
		} elseif ($a_bytes < 1208925819614629174706176) {
			return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
		} else {
			return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
		}
	}
}