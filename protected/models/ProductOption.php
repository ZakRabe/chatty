<?php

/**
 * This is the model class for table "product_option".
 *
 * The followings are the available columns in table 'product_option':
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $global_price
 * @property string $global_code
 *
 * The followings are the available model relations:
 * @property CartProductOption[] $cartProductOptions
 * @property OrderProductOption[] $orderProductOptions
 * @property ProductOptionGroup $group
 * @property ProductOptionLink[] $productOptionLinks
 * @property ProductPrice[] $productPrices
 */
class ProductOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductOption the static model class
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
		return 'product_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id, title, global_price, global_code', 'required'),
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>250),
			array('global_price, global_code', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, group_id, title, global_price, global_code', 'safe', 'on'=>'search'),
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
			'cartProductOptions' => array(self::HAS_MANY, 'CartProductOption', 'option_id'),
			'orderProductOptions' => array(self::HAS_MANY, 'OrderProductOption', 'option_id'),
			'group' => array(self::BELONGS_TO, 'ProductOptionGroup', 'group_id'),
			'productOptionLinks' => array(self::HAS_MANY, 'ProductOptionLink', 'option_id'),
			'productPrices' => array(self::HAS_MANY, 'ProductPrice', 'option_id'),

		);
	}
	
	
	
	public function scopes(){
        return array(
			
			'distinctGroup'=>array(
                'group'=>'group_id',
                //'order'=>'sort desc'
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
			'group_id' => 'Group',
			'title' => 'Title',
			'global_price' => 'Global Price',
			'global_code' => 'Global Code',
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
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('global_price',$this->global_price,true);
		$criteria->compare('global_code',$this->global_code,true);
		
		$criteria->addNotInCondition('title',array('GlobalPriceDummyOption'));
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(
                'defaultOrder' => 'sort asc',
            ),
		));
	}
	
	
	//Need a dummy Product Option for global prices due to the FK constraints on Product Price ie need a valid option_id...
	public function getGlobalPriceOptionId(){
		
		$option = ProductOption::model()->find('title=:title', array(':title'=>'GlobalPriceDummyOption')); 
		
		
		if($option != null){

			return $option->id;
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

			return $option->getPrimaryKey();
		}
	}
	
	// Generates the list of Product Options
	public function getDropList($id, $mode = 'dropList'){
		$options = ProductOption::model()->findAllByAttributes(array('group_id'=>$id), array('order'=>'sort'));
		
		switch($mode){
			case 'ajax':
				$info = array();
				foreach($options as $option){
					$info[] = array('id' => $option->id, 'title' => $option->title, 'price' => $option->global_price, 'code' => $option->global_code);
				}
				
				return $info;
			break;
			
			case 'create':
				$info = array();
				foreach($options as $option){
					$info[$option->id] = array('title' => $option->title, 'price' => $option->global_price, 'code' => $option->global_code);
				}
				
				return $info;
			break;
			
			case 'dropList':
				return CHtml::listData($options ,'id', 'title'); 
			break;
		}
		
	}
}