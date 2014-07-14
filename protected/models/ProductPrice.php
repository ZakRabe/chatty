<?php

/**
 * This is the model class for table "product_price".
 *
 * The followings are the available columns in table 'product_price':
 * @property integer $id
 * @property integer $product_id
 * @property integer $option_id
 * @property string $price
 * @property integer $sale
 * @property integer $date
 *
 * The followings are the available model relations:
 * @property CartProduct[] $cartProducts
 * @property OrderProduct[] $orderProducts
 * @property ProductOptionLink[] $productOptionLinks
 * @property Product $product
 * @property ProductOption $option
 */
class ProductPrice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductPrice the static model class
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
		return 'product_price';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, sale', 'required'),
			array('product_id, option_id, sale, date', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>18),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, option_id, price, sale, date', 'safe', 'on'=>'search'),
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
			'cartProducts' => array(self::HAS_MANY, 'CartProduct', 'price_id'),
			'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'price_id'),
			'productOptionLinks' => array(self::HAS_MANY, 'ProductOptionLink', 'price_id'),
			'product' => array(self::HAS_MANY, 'Product', 'product_id'),
			'option' => array(self::HAS_MANY, 'ProductOption', 'option_id'),
			//'products' => array(self::BELONGS_TO, 'Product', 'product_id')
			
			'productOptions'=>array(self::MANY_MANY, 'ProductOption', 'product_option_link(product_id, option_id)'),
		);
	}


	public function scopes()
    {
        return array(
			
			'latest'=>array(
                'order'=>'date DESC',
                'limit'=>1,
                'option_id' => ProductOption::model()->find('title=:title', array(':title'=>'GlobalPriceDummyOption'))
            ),
            /*[
			'byCategory' => array(
				'with' => array(
					'creator' => array(
						'with' => array(
							'creatorType' => array(
								'condition' => "creatorType.code = 'RETOUCH' AND t.published = 1 AND (isnull(t.parent_id) OR t.parent_id = '')",
							),
						),
					),
					'categories','galleryCatLinks',
				),

				'order' => 'categories.title DESC, galleryCatLinks.order',
            ),
			*/
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
			'option_id' => 'Option',
			'price' => 'Price',
			'sale' => 'Sale',
			'date' => 'Date',
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
		$criteria->compare('option_id',$this->option_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('sale',$this->sale);
		$criteria->compare('date',$this->date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function savePrice($id, $option_id, $price, $isSale, $return = 'result'){
		$model = new ProductPrice();
				
		$model->product_id = $id;
		$model->option_id = $option_id;
		$model->price = $price;
		$model->sale = $isSale;
		$model->date = time();
		
		if($return == 'result'){
			return $model->save();
		}else{
			$model->save();
			return $model->getPrimaryKey();
		}
	}
}