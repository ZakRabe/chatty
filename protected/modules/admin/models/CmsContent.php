<?php


class CmsContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CmsPage the static model class
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
		return 'cms_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content_left, content_left_column, content_right_column', 'required'),
			array('status, create_time, update_time, author_id', 'numerical', 'integerOnly'=>true),
			array('content_right', 'safe'), 
			array('title', 'length', 'max'=>128),
			array('content_middle', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			array('is_video', 'boolean'), 
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('title, content_left, content_left_column, content_right_column', 'safe', 'on'=>'search'),
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
			'author' => array(self::BELONGS_TO, 'Admin', 'author_id'),  //was linked to 'User'
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'content_left' => 'Top Left Content',
			'content_middle' => 'Top Middle Image (if any)',
			'is_video' => 'Check if video is to be displayed',
			'content_right' => 'Orange Call to Action Content',
			'content_left_column' => 'Body Left Content',
			'content_right_column' => 'Body Right Content',
			'status' => 'Status',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'author_id' => 'Author',
		);
	}
	
	public function getLink()
	{
		return CHtml::link(CHtml::encode($this->title), CController::createUrl('//p/view',array('id'=>$this->id,'title'=>$this->title)));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$userModel = Admin::model()->findByPk(Yii::app()->user->id);
		
		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
	
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content_left',$this->content_left,true);
		$criteria->compare('content_right',$this->content_right,true);
		$criteria->compare('content_left_column',$this->content_left_column,true);
		$criteria->compare('content_right_column',$this->content_right_column,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->create_time=$this->update_time=time();
				$this->author_id=Yii::app()->user->id;
				$this->status=1;
			}
			else
				$this->update_time=time();
			return true;
		}
		else
			return false;
	}
}
