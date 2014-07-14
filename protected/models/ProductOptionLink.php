<?php

/**
 * This is the model class for table "product_option_link".
 *
 * The followings are the available columns in table 'product_option_link':
 * @property integer $product_id
 * @property integer $option_id
 * @property integer $price_id
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property ProductOption $option
 * @property ProductPrice $price
 */
class ProductOptionLink extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductOptionLink the static model class
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
		return 'product_option_link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, option_id, price_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, option_id, price_id', 'safe', 'on'=>'search'),
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
			'option' => array(self::BELONGS_TO, 'ProductOption', 'option_id'),
			'price' => array(self::BELONGS_TO, 'ProductPrice', 'price_id'),
			'settings' => array(self::BELONGS_TO, 'ProductOptionSettings', 'setting_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'option_id' => 'Option',
			'price_id' => 'Price',
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

		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('option_id',$this->option_id);
		$criteria->compare('price_id',$this->price_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function saveLink($product_id, $option_id, $price_id, $setting_id, $return = 'result'){
		$model = new ProductOptionLink();
		
		$model->product_id = $product_id;
		$model->option_id = $option_id;
		$model->price_id = $price_id;
		$model->setting_id = $setting_id;
		
		if($return == 'result'){
			return $model->save();
		}else{
			$model->save();
			return $model->getPrimaryKey();
		}
	}
	
	public function updateLink($product_id, $option_id, $price_id){
		$model = ProductOptionLink::model()->findByAttributes(array('product_id'=>$product_id, 'option_id'=>$option_id));
		
		$model->price_id = $price_id;
		
		return $model->save();

	}
}