<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property string $keywords
 * @property string $description
 * @property string $description_detail
 * @property string $specs
 * @property string $weight
 * @property integer $feature
 * @property integer $views
 * @property integer $impressions
 * @property integer $global_price
 * @property integer $status
 * @property integer $modified
 * @property integer $created
 *
 * The followings are the available model relations:
 * @property CartProduct[] $cartProducts
 * @property OrderProduct[] $orderProducts
 * @property ProductCategoryLink[] $productCategoryLinks
 * @property ProductImage[] $productImages
 * @property ProductOptionLink[] $productOptionLinks
 * @property ProductPrice[] $productPrices
 */
class Product extends CActiveRecord
{
	public $category_id_filter;
	public $category_ids;
	
	public $price;
	public $isSale;
	
	public $option_ids;
	public $price_ids;
	
	public $optionGroup_ids;  //ProductOptionGroup
	public $options;
	
	
	public $images;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, code, keywords, description, description_detail, weight, category_ids, isSale', 'required'), //'price','specs' was here
			
			//Create
			array('price, images', 'required', 'on' => 'globalPriceCreate'),
			
			array('optionGroup_ids', 'required', 'on' => 'notGlobalPriceCreate', 'message'=>' Please select at least one Option Group.'), //{attribute}
			array('images', 'required', 'on' => 'notGlobalPriceCreate'),
			array('options', 'OptionAddedToGroup', 'on' => 'notGlobalPriceCreate'),
			array('options', 'CheckOptionPrice', 'on' => 'notGlobalPriceCreate'),
			
			//Update
			array('price', 'required', 'on' => 'globalPriceUpdate'),
			
			array('optionGroup_ids', 'required', 'on' => 'notGlobalPriceUpdate', 'message'=>' Please select at least one Option Group.'), //{attribute}
			array('options', 'OptionAddedToGroup', 'on' => 'notGlobalPriceUpdate'),
			array('options', 'CheckOptionPrice', 'on' => 'notGlobalPriceUpdate'),
			
			
			//doesn't work due to setting own defined scenario above (also can't combine when using $model->scenario = '' !!) - therefore need duplicates for Update and Create
			array('images', 'required', 'on' => 'insert'), // 'update' or 'insert'
			
			array('feature, views, impressions, global_price, status, modified, created', 'numerical', 'integerOnly'=>true),
			array('title, code, weight', 'length', 'max'=>250),
			array('isSale, status', 'boolean'),
			array('price', 'numerical', 'integerOnly'=>false),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, code, keywords, description, description_detail, specs, weight, feature, views, impressions, global_price, status, modified, created, category_id_filter', 'safe', 'on'=>'search'),
			
			array('status, feature', 'safe', 'on'=>'searchActive'),
			
			array('sort', 'required', 'on' => 'sort'),
			
			array('specs', 'safe')

		);
	}
	
	
	public function OptionAddedToGroup($attribute){  
	 	foreach($this->optionGroup_ids as $optionGroupId){
	 		if(!isset($this->options[$optionGroupId])){
		 		$this->addError('options_'.$optionGroupId, 'At least one Option needs to be added to the Group');
	 		}
	 	}
	}
	
	
	public function CheckOptionPrice($attribute){

	 	foreach($this->options as $optionGroupId => $optionGroup){
	 		foreach($optionGroup as $optionId => $option){
	 			if($optionId != 'optionGroupSettings'){
		 			if($option['options_price'] == ''){ //Product_options_4_13_options_price
				 		$this->addError('Product_options_'.$optionGroupId.'_'.$optionId.'_options_price', 'Price cannot be blank.');
			 		}elseif(!is_numeric($option['options_price'])){
				 		$this->addError('Product_options_'.$optionGroupId.'_'.$optionId.'_options_price', 'Price must be a number.');
			 		}
	 			}
		 		
	 		}
	 	}
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		//,'condition'=>'productCategorys.status = 1'
		return array(
			'cartProducts' => array(self::HAS_MANY, 'CartProduct', 'product_id'),
			'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'product_id'),
			'productCategoryLinks' => array(self::HAS_MANY, 'ProductCategoryLink', 'product_id'),
			'productImages' => array(self::HAS_MANY, 'ProductImage', 'product_id'),
			'productOptionLinks' => array(self::HAS_MANY, 'ProductOptionLink', 'product_id'),
			'productPrices' => array(self::HAS_MANY, 'ProductPrice', 'product_id'),

			//'productCategorys' => array(self::HAS_MANY, 'ProductCategory','category_id','through'=>'productCategoryLink'),
			'productCategories'=>array(self::MANY_MANY, 'ProductCategory', 'product_category_link(product_id, category_id)'),
			
			'productOptions'=>array(self::MANY_MANY, 'ProductOption', 'product_option_link(product_id, option_id)'),
			'productPricesLinked'=>array(self::MANY_MANY, 'ProductPrice', 'product_option_link(product_id, price_id)'),
			
			/*
			'productOptions1'=>array(self::MANY_MANY, 'ProductOption', 'product_option_link(product_id, option_id)',
				'with'=>'group'
			),
			*/
			
		);
	}
	
	public function behaviors(){
          return array( 'CAdvancedArBehavior' => array(
            'class' => 'application.extensions.CAdvancedArBehavior'));
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
			'keywords' => 'Keywords',
			'description' => 'Short Description',
			'description_detail' => 'Description Detail',
			'specs' => 'Specs',
			'weight' => 'Weight',
			'feature' => 'Featured',
			'views' => 'Views',
			'impressions' => 'Impressions',
			'global_price' => 'Global Price',
			'status' => 'Active',
			'modified' => 'Modified',
			'created' => 'Created',
			'category_ids' => 'Product Catergories',
			'price' => 'Price',
			'isSale' => 'Is on Sale',
			'optionGroup_ids' => 'Option Groups',
			'image' => 'Product Images',
		);
	}
	
	
	public function scopes()
    {
        return array(
            'published'=>array(
                'condition'=>'status=1',
            ),
            
            'sortedAZ'=>array(
                'order'=>'title',
            ),
            
            'allOptions'=>array(
                'with' => array(
					'productOptionLinks' => array(
						'with' => array(
							'option' => array(
								'with' => array(
									'group'
								),
							),
							'price',
							'settings',
						),
					),
					'productImages' => array(
						'order' => 'productImages.sort',
					)
				),

				'order' => 'group.sort, option.sort',
            ),
            
            
            'allOptionsDetail'=>array(
                'with' => array(
					'productOptionLinks' => array(
						'with' => array(
							'option' => array(
								'with' => array(
									'group'
								),
								'condition' => "(t.global_price = 1 AND option.id = ".ProductOption::model()->getGlobalPriceOptionId().") OR (t.global_price = 0 AND option.id <> ".ProductOption::model()->getGlobalPriceOptionId().")"
							),
							'price',
							'settings'=> array(
								'condition' => "settings.status = 1 AND settings.group_status = 1 ",
							),
						),
					),
					'productImages' => array(
						'condition' => "productImages.sort = 0",
					),
					'productCategories' => array(
						'condition' => "productCategories.status = 1",
					),
				),
				'condition' => "t.status = 1",
				'order' => 'group.sort, option.sort',
            ),
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
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('description_detail',$this->description_detail,true);
		$criteria->compare('specs',$this->specs,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('feature',$this->feature);
		$criteria->compare('views',$this->views);
		$criteria->compare('impressions',$this->impressions);
		$criteria->compare('global_price',$this->global_price);
		$criteria->compare('status',$this->status);
		$criteria->compare('modified',$this->modified);
		$criteria->compare('created',$this->created);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>30)
		));
	}
	
	public function searchAdmin()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('description_detail',$this->description_detail,true);
		$criteria->compare('specs',$this->specs,true);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('feature',$this->feature);
		$criteria->compare('views',$this->views);
		$criteria->compare('impressions',$this->impressions);
		$criteria->compare('global_price',$this->global_price);
		$criteria->compare('t.status',$this->status);  //to advoid unambiguous column errors - bit of a hack!!
		$criteria->compare('modified',$this->modified);
		$criteria->compare('created',$this->created);
		
		if(isset($this->category_id_filter) && $this->category_id_filter > 0){
			$criteria->with = array( 'productCategories' );
			$criteria->together = true;
			$criteria->compare( 'productCategories.id', $this->category_id_filter);
		}
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>30),
			'sort' => array(
                'defaultOrder' => 't.sort asc',
            ),
		));
	}
	
	
	public function searchListsPage($categoryId)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.     
            
		$criteria=new CDbCriteria;
		
		$criteria->together = true;
		
		$criteria->with = array(
			'productOptionLinks',
			'productOptionLinks.option',
			'productOptionLinks.option.group',
			'productOptionLinks.price',
			'productOptionLinks.settings',
			'productImages',
			'productCategories'
		);
		
		$criteria->condition = "(t.global_price=:globalPriceFlagOn AND option.id=:globalPriceOptionId) OR (t.global_price=:globalPriceFlagOff AND option.id<>:globalPriceOptionId)";
		$criteria->params = array(':globalPriceFlagOn' => 1, ':globalPriceFlagOff' => 0, ':globalPriceOptionId' => ProductOption::model()->getGlobalPriceOptionId());

		$criteria->order = 't.title, group.sort, option.sort';		
		  
		$criteria->compare('productImages.sort', 0);
		
		$criteria->compare('t.status',1);  //to advoid unambiguous column errors - bit of a hack!!
		$criteria->compare('settings.status',1);
		$criteria->compare('settings.group_status',1);
		$criteria->compare('productCategories.status',1);
		
		if($categoryId != 0){
			$criteria->compare('productCategories.id',$categoryId);
		}else{
			$criteria->compare('feature',1);
		}
		
		$dataProvider = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>8888), //NOTE if set this to 8 then only returns 8 records - if one product has 3 options then this counts as 3 records!!!!!! if turn off, default to 10!!! But yii combines all records later on...

		));
		
		
		return $dataProvider;
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
				$this->modified=time();
			}
			
			return true;
		}
		else
			return false;
	}
}