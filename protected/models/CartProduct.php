<?php

/**
 * This is the model class for table "cart_product".
 *
 * The followings are the available columns in table 'cart_product':
 * @property integer $id
 * @property integer $cart_id
 * @property integer $product_id
 * @property integer $price_id
 * @property integer $quantity
 *
 * The followings are the available model relations:
 * @property Cart $cart
 * @property Product $product
 * @property ProductPrice $price
 * @property CartProductOption[] $cartProductOptions
 */
class CartProduct extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CartProduct the static model class
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
		return 'cart_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cart_id, product_id, price_id, quantity', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cart_id, product_id, price_id, quantity', 'safe', 'on'=>'search'),
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
			'cart' => array(self::BELONGS_TO, 'Cart', 'cart_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'price' => array(self::BELONGS_TO, 'ProductPrice', 'price_id'),
			'cartProductOptions' => array(self::HAS_MANY, 'CartProductOption', 'cart_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cart_id' => 'Cart',
			'product_id' => 'Product',
			'price_id' => 'Price',
			'quantity' => 'Quantity',
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
		$criteria->compare('cart_id',$this->cart_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('price_id',$this->price_id);
		$criteria->compare('quantity',$this->quantity);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	// Creates a new cart product
	public function create($cartID, $productID, $priceID, $quantity, $return = 'result'){
		$model = new CartProduct();
		
		$model->cart_id = $cartID;
		$model->product_id = $productID;
		$model->price_id = $priceID;
		$model->quantity = $quantity;

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