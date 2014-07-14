<?php

/**
 * This is the model class for table "product_option_settings".
 *
 * The followings are the available columns in table 'product_option_settings':
 * @property integer $id
 * @property integer $status
 * @property integer $group_status
 * @property integer $use_custom_code
 * @property string $code
 * @property integer $use_option_global_price
 * @property integer $deleted
 */
class ProductOptionSettings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductOptionSettings the static model class
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
		return 'product_option_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status, group_status, use_custom_code, use_option_global_price, deleted', 'required'),
			array('status, group_status, use_custom_code, use_option_global_price, deleted', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, group_status, use_custom_code, code, use_option_global_price, deleted', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Status',
			'group_status' => 'Group Status',
			'use_custom_code' => 'Use Custom Code',
			'code' => 'Code',
			'use_option_global_price' => 'Use Option Global Price',
			'deleted' => 'Deleted',
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
		$criteria->compare('status',$this->status);
		$criteria->compare('group_status',$this->group_status);
		$criteria->compare('use_custom_code',$this->use_custom_code);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('use_option_global_price',$this->use_option_global_price);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	//Need a dummy Product Option Settings for global prices due to the FK constraints on Product Option Link ie need a valid seting_id...
	public function getGlobalProductOptionSettingId(){
		
		$setting = ProductOptionSettings::model()->find('code=:code', array(':code'=>'GlobalPriceDummySetting')); 
		
		
		if($setting != null){

			return $setting->id;
		}else{
			$setting = new ProductOptionSettings();
			$setting->status = 1;
			$setting->group_status = 1;
			$setting->use_custom_code = 0;
			$setting->code = 'GlobalPriceDummySetting';
			$setting->use_option_global_price = 0;
			$setting->deleted = 0;
			$setting->save();

			return $setting->getPrimaryKey();
		}
	}
	
	public function saveSetting($status, $group_status=1, $use_custom_code = 0, $code = '', $use_option_global_price = 0, $deleted = 0, $return = 'result'){
	
		$model = new ProductOptionSettings();
				
		$model->status = $status;
		$model->group_status = $group_status;
		$model->use_custom_code = $use_custom_code;
		$model->code = $code;
		$model->use_option_global_price = $use_option_global_price;
		$model->deleted = $deleted;
		
		if($return == 'result'){
			return $model->save();
		}else{
			$model->save();
			return $model->getPrimaryKey();
		}
	}
	
	public function updateSettings($product_id, $option_id, $status, $group_status=1, $use_custom_code = 0, $code = '', $use_option_global_price = 0, $deleted = 0, $return = 'result'){
	
		$productOptionLink = ProductOptionLink::model()->findByAttributes(array('product_id'=>$product_id, 'option_id'=>$option_id));
		
		$model = ProductOptionSettings::model()->findByPK($productOptionLink->setting_id);	
		$model->status = $status;
		$model->group_status = $group_status;
		$model->use_custom_code = $use_custom_code;
		$model->code = $code;
		$model->use_option_global_price = $use_option_global_price;
		$model->deleted = $deleted;
		
		return $model->save();
	}

	
	
}