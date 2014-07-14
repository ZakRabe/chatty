<?php

/**
 * This is the model class for table "product_category".
 *
 * The followings are the available columns in table 'product_category':
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $description_detail
 * @property integer $status
 * @property integer $modified
 * @property integer $created
 *
 * The followings are the available model relations:
 * @property ProductCategoryLink[] $productCategoryLinks
 */
class ProductCategory extends CActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductCategory the static model class
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
		return 'product_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, parent_id, keywords, description, description_detail', 'required'),
			array('parent_id, status, modified, created', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>250),
			array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, title, keywords, description, description_detail, status, modified, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		
		//,'order'=>'productCategoryLink.order ASC'
		
		return array(
			'productCategoryLinks' => array(self::HAS_MANY, 'ProductCategoryLink', 'category_id'),
			//'products' => array(self::HAS_MANY, 'Product','product_id','through'=>'productCategoryLink')
			'products'=>array(self::MANY_MANY, 'Product', 'product_category_link(product_id, category_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Parent',
			'title' => 'Title',
			'image' => 'Image',
			'keywords' => 'Keywords',
			'description' => 'Description',
			'description_detail' => 'Description Detail',
			'status' => 'Status',
			'modified' => 'Modified',
			'created' => 'Created',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('description_detail',$this->description_detail,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('modified',$this->modified);
		$criteria->compare('created',$this->created);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
                'defaultOrder' => 't.sort asc',
            ),
		));
	}
	
	 protected function beforeSave()
	{

		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->created=time();
				$this->modified=time();
			}else{
				if(Yii::app()->controller->action->id != 'order') $this->modified=time(); //so no update time if reordering via ajax
			}
			
			return true;
		}
		else
			return false;
	}
	
	
	// Generates the list of Product Categories for the various dropdown lists
	// Also generates options for Product Category each item ie if it is disabled or not
	public function getCategoriesList($mode='dropList',$id=0, $level = 0){
		$catList = array();
		$optionsList = array();
		$attrubutes = array('parent_id'=>$id);
		
		if($mode == 'frontEnd'){
			$attrubutes['status'] = 1;
		}
		
		$categories = ProductCategory::model()->findAllByAttributes($attrubutes, array('order'=>'`sort`'));
		
		foreach($categories as $category){
			if($level != 0 && ($mode == 'dropListMulti' || $mode == 'dropList')){
				//$catList[$category->id] = str_pad('', $level , " ").'&#2021;'.$category->title; //using google fonts that don't support this char!!
				$catList[$category->id] = str_replace(' ','&nbsp;',str_pad('', $level*2 , " ")).'- '.$category->title;  //&lfloor;
			}else{
				$catList[$category->id] = $category->title;
			}
			
			$optionsList[$category->id] = array();
			$optionsList[$category->id]['level'] = $level;
			$optionsList[$category->id]['title'] = $category->title;
			
			if(!in_array($level, Yii::app()->params['productAtCategoryLevels'])) $optionsList[$category->id]['disabled'] = true;
			if($mode == 'checkBoxes' || $mode == 'frontEnd') $optionsList[$category->id]['level'] = $level;
			if($mode == 'frontEnd'){
				$optionsList[$category->id]['image'] = $category->image;
			}
			
			$return = $this->getCategoriesList($mode, $category->id, $level+1);
			$catList = $catList + $return['catList'];
			$optionsList = $optionsList + $return['optionsList'];
		}
		
		return array('optionsList' => $optionsList, 'catList' => $catList);
	}
	
	
	// determines at what nesting level a certain Product Category is at
	public function getLevel($id, $level=0){
		
		$category = ProductCategory::model()->findByPK($id);
		
		if($category->parent_id != 0){
			return $this->getLevel($category->parent_id, $level+1);
		}
		return $level;
	}
}