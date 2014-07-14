<?php

class ProfileField extends CActiveRecord
{
	const VISIBLE_ALL=3;
	const VISIBLE_REGISTER_USER=2;
	const VISIBLE_ONLY_OWNER=1;
	const VISIBLE_NO=0;
	
	const REQUIRED_NO = 0;
	const REQUIRED_YES_SHOW_REG = 1;
	const REQUIRED_NO_SHOW_REG = 2;
	const REQUIRED_YES_NOT_SHOW_REG = 3;
	
	/**
	 * The followings are the available columns in table 'profiles_fields':
	 * @var integer $id
	 * @var string $varname
	 * @var string $title
	 * @var string $field_type
	 * @var integer $field_size
	 * @var integer $field_size_mix
	 * @var integer $required
	 * @var integer $match
	 * @var string $range
	 * @var string $error_message
	 * @var string $other_validator
	 * @var string $default
	 * @var integer $position
	 * @var integer $visible
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
		return Yii::app()->getModule('admin')->tableProfileFields;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('varname, title, field_type', 'required'),
			array('varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u','message' => AdminModule::t("Variable name may consist of A-z, 0-9, underscores, begin with a letter.")),
			array('varname', 'unique', 'message' => AdminModule::t("This field already exists.")),
			array('varname, field_type', 'length', 'max'=>50),
			array('field_size, field_size_min, required, position, visible', 'numerical', 'integerOnly'=>true),
			array('title, match, error_message, other_validator, default, widget', 'length', 'max'=>255),
			array('range, widgetparams', 'length', 'max'=>5000),
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
			'id' => AdminModule::t('Id'),
			'varname' => AdminModule::t('Variable name'),
			'title' => AdminModule::t('Title'),
			'field_type' => AdminModule::t('Field Type'),
			'field_size' => AdminModule::t('Field Size'),
			'field_size_min' => AdminModule::t('Field Size min'),
			'required' => AdminModule::t('Required'),
			'match' => AdminModule::t('Match'),
			'range' => AdminModule::t('Range'),
			'error_message' => AdminModule::t('Error Message'),
			'other_validator' => AdminModule::t('Other Validator'),
			'default' => AdminModule::t('Default'),
			'widget' => AdminModule::t('Widget'),
			'widgetparams' => AdminModule::t('Widget parametrs'),
			'position' => AdminModule::t('Position'),
			'visible' => AdminModule::t('Visible'),
		);
	}
	
	public function scopes()
    {
        return array(
            'forAll'=>array(
                'condition'=>'visible='.self::VISIBLE_ALL,
                'order'=>'position',
            ),
            'forUser'=>array(
                'condition'=>'visible>='.self::VISIBLE_REGISTER_USER,
                'order'=>'position',
            ),
            'forOwner'=>array(
                'condition'=>'visible>='.self::VISIBLE_ONLY_OWNER,
                'order'=>'position',
            ),
            'forRegistration'=>array(
                'condition'=>'required='.self::REQUIRED_NO_SHOW_REG.' OR required='.self::REQUIRED_YES_SHOW_REG,
                'order'=>'position',
            ),
            'sort'=>array(
                'order'=>'position',
            ),
        );
    }
    
    /**
     * @param $value
     * @return formated value (string)
     */
    public function widgetView($model) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'viewAttribute')) {
				return $widgetClass->viewAttribute($model,$this);
			}
		} 
		return false;
    }
    
    public function widgetEdit($model,$params=array()) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'editAttribute')) {
				return $widgetClass->editAttribute($model,$this,$params);
			}
		}
		return false;
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'field_type' => array(
				'INTEGER' => AdminModule::t('INTEGER'),
				'VARCHAR' => AdminModule::t('VARCHAR'),
				'TEXT'=> AdminModule::t('TEXT'),
				'DATE'=> AdminModule::t('DATE'),
				'FLOAT'=> AdminModule::t('FLOAT'),
				'BOOL'=> AdminModule::t('BOOL'),
				'BLOB'=> AdminModule::t('BLOB'),
				'BINARY'=> AdminModule::t('BINARY'),
			),
			'required' => array(
				self::REQUIRED_NO => AdminModule::t('No'),
				self::REQUIRED_NO_SHOW_REG => AdminModule::t('No, but show on registration form'),
				self::REQUIRED_YES_SHOW_REG => AdminModule::t('Yes and show on registration form'),
				self::REQUIRED_YES_NOT_SHOW_REG => AdminModule::t('Yes'),
			),
			'visible' => array(
				self::VISIBLE_ALL => AdminModule::t('For all'),
				self::VISIBLE_REGISTER_USER => AdminModule::t('Registered users'),
				self::VISIBLE_ONLY_OWNER => AdminModule::t('Only owner'),
				self::VISIBLE_NO => AdminModule::t('Hidden'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
}