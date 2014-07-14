<?php

class Admin extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANED=-1;
	
	public $salt;
    public $repeat_password;
	public $password_old;
	
	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
	 * @var integer $createtime
	 * @var integer $lastvisit
	 * @var integer $superuser
	 * @var integer $status
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return Yii::app()->getModule('admin')->tableUsers;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		
		return ((Yii::app()->getModule('admin')->isAdmin())?array(
			#array('username, password, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => AdminModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => AdminModule::t("Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => AdminModule::t("This user's name already exists.")),
			array('email', 'unique', 'message' => AdminModule::t("This user's email address already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => AdminModule::t("Incorrect symbols (A-z0-9).")),
			array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE,self::STATUS_ACTIVE,self::STATUS_BANED)),
			array('superuser', 'in', 'range'=>array(0,1)),
			array('username, email, createtime, lastvisit, superuser, status', 'required'),
			array('createtime, lastvisit, superuser, status', 'numerical', 'integerOnly'=>true),
		):((Yii::app()->user->id==$this->id)?array(
			array('username, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => AdminModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => AdminModule::t("This user's name already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => AdminModule::t("Incorrect symbols (A-z0-9).")),
			array('email', 'unique', 'message' => AdminModule::t("This user's email address already exists.")),
		):array()));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		$relations = array(
			'profile'=>array(self::HAS_ONE, 'Profile', 'user_id'),
		);
		if (isset(Yii::app()->getModule('admin')->relations)) $relations = array_merge($relations,Yii::app()->getModule('admin')->relations);
		return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>AdminModule::t("username"),
			'password'=>AdminModule::t("password"),
			'verifyPassword'=>AdminModule::t("Retype Password"),
			'email'=>AdminModule::t("E-mail"),
			'verifyCode'=>AdminModule::t("Verification Code"),
			'id' => AdminModule::t("Id"),
			'activkey' => AdminModule::t("activation key"),
			'createtime' => AdminModule::t("Registration date"),
			'lastvisit' => AdminModule::t("Last visit"),
			'superuser' => AdminModule::t("Superuser"),
			'status' => AdminModule::t("Status"),
		);
	}
	
	public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactive'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            'banned'=>array(
                'condition'=>'status='.self::STATUS_BANED,
            ),
            'superuser'=>array(
                'condition'=>'superuser=1',
            ),
            'notsafe'=>array(
            	'select' => 'id, username, password, email, activkey, createtime, lastvisit, superuser, status',
            ),
        );
    }
	
	public function defaultScope()
    {
        return array(
            'select' => 'id, username, email, createtime, lastvisit, superuser, status',
        );
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOACTIVE => AdminModule::t('Not active'),
				self::STATUS_ACTIVE => AdminModule::t('Active'),
				self::STATUS_BANED => AdminModule::t('Banned'),
			),
			'AdminStatus' => array(
				'0' => AdminModule::t('No'),
				'1' => AdminModule::t('Yes'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('superuser',$this->superuser,true);
		$criteria->compare('FROM_UNIXTIME("d/m/Y",createtime)',$this->createtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
    public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->password;
    }
 
    public function hashPassword($password,$salt='')
    {
        return md5($salt.$password);
    }
    
    protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->createtime=time();
				$this->salt=md5($this->email.time());
				//$this->password=$this->hashPassword($this->password,$this->salt);
				
				//LoginController does not use salt
				//ie encrypting() function in /protected/modules/admin/AdminModule.php
				//Also if change password in UsersController actionUpdate() also does not set salt
				$this->password=$this->hashPassword($this->password,''); 
			}
			
			return true;
		}
		else
			return false;
	}
}
