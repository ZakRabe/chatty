<?php

/**
 * This is the model class for table "cart_product_option".
 *
 * The followings are the available columns in table 'cart_product_option':
 * @property integer $id
 * @property integer $cart_product_id
 * @property integer $option_id
 * @property integer $price_id
 *
 * The followings are the available model relations:
 * @property CartProduct $cartProduct
 * @property ProductOption $option
 */
class CartProductOption extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CartProductOption the static model class
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
		return 'cart_product_option';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cart_product_id, option_id, price_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cart_product_id, option_id, price_id', 'safe', 'on'=>'search'),
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
			'cartProduct' => array(self::BELONGS_TO, 'CartProduct', 'cart_product_id'),
			'option' => array(self::BELONGS_TO, 'ProductOption', 'option_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cart_product_id' => 'Cart Product',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('cart_product_id',$this->cart_product_id);
		$criteria->compare('option_id',$this->option_id);
		$criteria->compare('price_id',$this->price_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	// Creates a new cart product option
	public function create($cartProductID, $optionID, $priceID, $return = 'result'){
		$model = new CartProductOption();
		
		$model->cart_product_id = $cartProductID;
		$model->option_id = $optionID;
		$model->price_id = $priceID;

		$model->save();
		$id = $model->getPrimaryKey();
		
		if($return == 'result'){
			return $model->save();
		}else{
			$model->save();
			return $id;
		}
	}
}