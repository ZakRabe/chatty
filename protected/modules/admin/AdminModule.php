<?php

class AdminModule extends CWebModule 
{
	public $frontendTheme = '';

	// shop
	// Names of the tables
	public $categoryTable = 'shop_category';
	public $productsTable = 'shop_products';
	public $orderTable = 'shop_order';
	public $orderPositionTable = 'shop_order_position';
	public $customerTable = 'shop_customer';
	public $addressTable = 'shop_address';
	public $imageTable = 'shop_image';
	public $shippingMethodTable = 'shop_shipping_method';
	public $paymentMethodTable = 'shop_payment_method';
	public $taxTable = 'shop_tax';
	public $productSpecificationTable = 'shop_product_specification';
	public $productVariationTable = 'shop_product_variation';
	public $currencySymbol = '$';
	public $productView = 'view';

	// Set this to a valid email address to send a message once a order
	// comes in.
	public $orderNotificationEmail = false;
	public $orderNotificationFromEmail = 'do@not-reply.org';
	public $orderNotificationReplyEmail = 'do@not-reply.org';

	public $enableLogging = true;

	public $titleOptions = array('mr' => 'Mr.', 'ms' => 'Mrs.');

	// See docs/tcpdf.txt on how to enable PDF Generation of Invoices
	public $useTcPdf = false;
	public $tcPdfPath = 'ext.tcpdf.tcpdf';
	public $slipViewPdf = '/order/pdf/slip';
	public $invoiceViewPdf = '/order/pdf/invoice';
	public $footerViewPdf = '/order/pdf/footer';

	public $logoPath = 'logo.jpg';

	// Set this to an array to only allow various countries, for example
	// public $validCountries = array('Germany', 'Swiss', 'China'),
	public $validCountries = null;

	public $slipView = '/order/slip';
	public $invoiceView = '/order/invoice';
	public $footerView = '/order/footer';

	public $dateFormat = 'd/m/Y';

	// Set this to the id of the weight specification to enable weight
	// calculation in the delivery slip and invoice. 1 is for the demo
	// data. Set to NULL to disable weight calculation.
	public $weightSpecificationId = 1;
	
	public $imageWidthThumb = 100;
	public $imageWidth = 200;

	public $notifyAdminEmail = null;

	// If a price is NULL in the database, which price should be used instead?
	public $defaultPrice = 0.00;

	public $termsView = '/order/terms';
	public $successAction = array('//shop/order/success');
	public $failureAction = array('//shop/order/failure');

	public $orderConfirmTemplate = "Dear {title} {firstname} {lastname}, \n your order #{order_id} has been taken";

	// Where the uploaded product images are stored, started from approot/:
	public $productImagesFolder = 'assets/productimages'; 

	// Images uploaded by the customer (for example, for Poster Shops)
	public $uploadedImagesFolder = 'uploadedimages'; 

	//public $adminLayout = 'application.modules.shop.views.layouts.shop';
	//public $layout = 'application.modules.shop.views.layouts.shop';

	// Set this to enable Paypal payment. See docs/paypal.txt
	public $payPalMethod = false;
	public $payPalTestMode = true;
	public $payPalUrl = '//shop/order/paypal';
	public $payPalBusinessEmail = 'webmaster@example.com';

	// Rich text editor for the product description textarea.
	// for example, set this to the path of your ckeditor installation
	// to enable it
	public $rtepath = false; // Don't use an Rich text Editor
	public $rteadapter = false; // Don't use an Adapter


	// Set $allowPositionLiveChange to false if you have too many Variations in
	// an article. Changing of variations is not possible in the shopping cart
	// view anymore then.
	public $allowPositionLiveChange = true;
	
	
	// admin users
	/**
	 * @var int
	 * @desc items on page
	 */
	public $user_page_size = 10;
	
	/**
	 * @var int
	 * @desc items on page
	 */
	public $fields_page_size = 10;
	
	/**
	 * @var string
	 * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
	 */
	public $hash='md5';
	
	/**
	 * @var boolean
	 * @desc use email for activation user account
	 */
	public $sendActivationMail=true;
	
	/**
	 * @var boolean
	 * @desc allow auth for is not active user
	 */
	public $loginNotActiv=false;
	
	/**
	 * @var boolean
	 * @desc activate user on registration (only $sendActivationMail = false)
	 */
	public $activeAfterRegister=false;
	
	/**
	 * @var boolean
	 * @desc login after registration (need loginNotActiv or activeAfterRegister = true)
	 */
	public $autoLogin=true;
	
	//public $registrationUrl = array("/admin/registration");
	//public $recoveryUrl = array("/user/recovery/recovery");
	public $loginUrl = array("/admin/login");
	public $logoutUrl = array("/admin/logout");
	public $profileUrl = array("/admin/profile");
	public $returnUrl = array("/admin/");
	public $returnLogoutUrl = "/admin/";
	
	public $fieldsMessage = '';
	
	/**
	 * @var array
	 * @desc User model relation from other models
	 * @see http://www.yiiframework.com/doc/guide/database.arr
	 */
	public $relations = array();
	
	/**
	 * @var array
	 * @desc Profile model relation from other models
	 */
	public $profileRelations = array();
	
	/**
	 * @var boolean
	 */
	public $captcha = array('registration'=>true);
	
	/**
	 * @var boolean
	 */
	//public $cacheEnable = false;
	
	public $tableUsers = '{{admin}}';
	public $tableProfiles = '{{admin_profiles}}';
	public $tableProfileFields = '{{admin_profiles_fields}}';
	
	static private $_user;
	static private $_admin;
	static private $_admins;
	static private $_superuser;
	static private $_superusers;
	
	/**
	 * @var array
	 * @desc Behaviors for models
	 */
	public $componentBehaviors=array();
	
	
	public function init()
	{
		
		$this->setImport(array(
			'application.modules.admin.models.*',
			'application.modules.admin.components.*',
		));
		
		Yii::app()->user->loginUrl = $this->loginUrl;
		$this->frontendTheme = Yii::app()->theme;
		Yii::app()->theme = 'reactor';
	}
	
	public function getBehaviorsFor($componentName){
        if (isset($this->componentBehaviors[$componentName])) {
            return $this->componentBehaviors[$componentName];
        } else {
            return array();
        }
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str='',$params=array(),$dic='admin') {
		return Yii::t("AdminModule.".$dic, $str, $params);
	}
	
	/**
	 * @return hash string.
	 */
	public static function encrypting($string="") {
		$hash = Yii::app()->getModule('admin')->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
	
	/**
	 * @param $place
	 * @return boolean 
	 */
	public static function doCaptcha($place = '') {
		if(!extension_loaded('gd'))
			return false;
		if (in_array($place, Yii::app()->getModule('admin')->captcha))
			return Yii::app()->getModule('admin')->captcha[$place];
		return false;
	}
	
	/**
	 * Return admin status.
	 * @return boolean
	 */
	public static function isAdmin() {
		if(Yii::app()->user->isGuest)
			return false;
		else {
			if (!isset(self::$_admin)) {
				if(self::user()->superuser)
					self::$_admin = true;
				else
					self::$_admin = false;	
			}
			return self::$_admin;
		}
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */	
	public static function getAdmins() {
		if (!self::$_admins) {
			$admins = Admin::model()->active()->findAll();
			$return_name = array();
			foreach ($admins as $admin)
				array_push($return_name,$admin->username);
			self::$_admins = $return_name;
		}
		return self::$_admins;
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */	
	public static function getSuperUsers() {
		if (!self::$_admins) {
			$admins = Admin::model()->active()->superuser()->findAll();
			$return_name = array();
			foreach ($admins as $admin)
				array_push($return_name,$admin->username);
			self::$_admins = $return_name;
		}
		return self::$_admins;
	}
	
	/**
	 * Send mail method
	 */
	public static function sendMail($email,$subject,$message) {
    	$adminEmail = Yii::app()->params['adminEmail'];
	    $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
	    $message = wordwrap($message, 70);
	    $message = str_replace("\n.", "\n..", $message);
	    return mail($email,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public static function user($id=0) {
		if ($id) 
			return Admin::model()->active()->findbyPk($id);
		else {
			if(Yii::app()->user->isGuest) {
				return false;
			} else {
				if (!self::$_user)
					self::$_user = Admin::model()->active()->findbyPk(Yii::app()->user->id);
				return self::$_user;
			}
		}
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public function users() {
		return User;
	}
}
