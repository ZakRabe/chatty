<?php

class Upload extends CFormModel
{
    public $image;
    
    /* options: */
	public $allowed_types = array(
		"image/jpeg", 
		"image/jpg", 
		"image/pjpeg", 
		"image/pjpg",
		"image/gif",
		"image/png"
	);
	public $images_dir = "uploads"; // dir located in assets
	public $cache_dir = "cache"; // dir located in assets
	public $find_limit = 20; // results per page
	public $thumbnail_width = 25;
	public $thumbnail_height = 25;
	/* END options; */
        
    public function rules()
    {
        return array(
            array('image', 'unsafe'),
        );
    }
    
    public function save() 
    {
		return $this->image->saveAs(Yii::app()->assetManager->basePath . "/" . $this->images_dir . "/" . $this->image->name);
	}
	
	public function byte_convert($bytes) {
		$symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');

		$exp = 0;
		$converted_value = 0;
		if( $bytes > 0 )
		{
		  $exp = floor( log($bytes)/log(1024) );
		  $converted_value = ( $bytes/pow(1024,floor($exp)) );
		}

		return sprintf( '%.2f '.$symbol[$exp], $converted_value );
	}
	
}