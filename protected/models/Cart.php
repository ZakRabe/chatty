<?php

/**
 * This is the model class for table "cart".
 *
 * The followings are the available columns in table 'cart':
 * @property integer $id
 * @property string $sess_id
 * @property integer $user_id
 * @property integer $created
 * @property integer $modified
 *
 * The followings are the available model relations:
 * @property User $user
 * @property CartProduct[] $cartProducts
 */
class Cart extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Cart the static model class
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
		return 'cart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, created, modified', 'numerical', 'integerOnly'=>true),
			array('sess_id', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sess_id, user_id, created, modified', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'cartProducts' => array(self::HAS_MANY, 'CartProduct', 'cart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sess_id' => 'Sess',
			'user_id' => 'User',
			'created' => 'Created',
			'modified' => 'Modified',
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
		$criteria->compare('sess_id',$this->sess_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('modified',$this->modified);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	
	// Creates a new cart 
	public function create($sessionID, $userID = 0, $return = 'result'){
		$model = new Cart();
		
		$model->sess_id = $sessionID;
		$model->user_id = $userID;

		$model->save();
		$id = $model->getPrimaryKey();
		
		if($return == 'result'){
			return $model->save();
		}else{
			$model->save();
			return $id;
		}
	}
	
	protected function beforeSave(){

		if(parent::beforeSave()){
			if($this->isNewRecord){
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