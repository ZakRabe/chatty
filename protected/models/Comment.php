<?php

class Comment extends CActiveRecord
{
  const TABLE_NAME = "comments";
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// all fields are required
			array('text, name, city, email', 'required'),
			array('text, name, city, email', 'safe'),
			// email has to be a valid email address
			array('email', 'email'),
      // all fields cant have default text
      array('text, name, city', 'defaults', ),
		);
	}
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
    return self::TABLE_NAME;
  }

  public function defaults($attribute)
  {
    $defaults = array("Tell us how a simple chat on public transport made you smile, made you think, made your day, or ultimately changed your life...","Name", "City");
    
    switch ($attribute) {
      case 'text':
        $id = 0;
        $error = "Please enter a story.";
        break;
      case 'name':
        $id = 1;
        $error = "Please enter your name.";
        break;
      case 'city':
        $id = 2;
        $error = "Please enter your city.";
        break;
    }
    if ($this->$attribute == $defaults[$id]){
      $this->addError($attribute, $error);
    }
  }
}