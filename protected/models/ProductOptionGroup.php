<?php

/**
 * This is the model class for table "product_option_group".
 *
 * The followings are the available columns in table 'product_option_group':
 * @property integer $id
 * @property string $title
 * @property string $code
 *
 * The followings are the available model relations:
 * @property ProductOption[] $productOptions
 */
class ProductOptionGroup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductOptionGroup the static model class
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
		return 'product_option_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title', 'length', 'max'=>250),
			array('code', 'length', 'max'=>6),
			array('title', 'unique'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, code', 'safe', 'on'=>'search'),
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
			'productOptions' => array(self::HAS_MANY, 'ProductOption', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'code' => 'Code',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		
		$criteria->addNotInCondition('title',array('GlobalPriceDummyGroup'));
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
                'defaultOrder' => 'sort asc',
            ),
		));
	}
	
	//Need a dummy Product Option for global prices due to the FK constraints on Product Price ie need a valid option_id...
	public function getGlobalPriceOptionGroupId(){
		
		$optionGroup = ProductOptionGroup::model()->find('title=:title', array(':title'=>'GlobalPriceDummyGroup')); 
		
		if($optionGroup != null){

			return $optionGroup->id;
		}else{
			
			$optionGroup = new ProductOptionGroup();
			$optionGroup->title = 'GlobalPriceDummyGroup';
			$optionGroup->code = 'prefix';
			$optionGroup->save();
			
			$option = new ProductOption();
			$option->group_id = $optionGroup->getPrimaryKey();
			$option->title = 'GlobalPriceDummyOption';
			$option->global_price = '999999.99';
			$option->global_code = 'DUMMY';
			$option->save();

			return $optionGroup->getPrimaryKey();
		}
	}
	
	// Generates the list of Product Option Groups
	public function getDropList($mode = 'dropList'){
		$options = ProductOptionGroup::model()->findAll(array('condition'=>'title !=:title', 'params'=>array(':title'=>'GlobalPriceDummyGroup'),'order'=>'sort'));
		
		switch($mode){
			case 'create':
				$info = array();
				foreach($options as $option){
					$info[$option->id] = array('title' => $option->title, 'code' => $option->code);
				}
				
				return $info;
			break;
			
			case 'dropList':
				return CHtml::listData($options,'id', 'title');
			break;
		} 
	}
}