<?php 

namespace controller;

require_once("model/BLL/Image.php");
require_once("model/BLL/Product.php");
require_once("model/ProductModel.php");
require_once("view/HTMLView.php");
require_once("view/CreateProductView.php");
require_once("view/ProductListView.php");

class AdminController {

	private $connect;
	private $model;
	private $html;
	private $cpv;
	private $plv;

	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	public function __construct() {
		$this->connect = new \model\ConnectDB(\Settings::SERVER, 
												\Settings::DATABASE,
												\Settings::DB_USERNAME,
												\Settings::DB_PASSWORD);
		
		try {
			$this->model = new \model\ProductModel($this->connect->getConnection());
		}
		catch (\DatabaseConnectionException $e) {
			echo $e->getMessage();
		}

		$this->html = new \view\HTMLView();
		$this->cpv = new \view\CreateProductView();
		$this->plv = new \view\ProductListView($this->model);
	}

	public function doControl() {
		if($this->cpv->adminWantsToAddProduct()) {
			$image = $this->cpv->getImage();

			if($image !== null) {
				$p = $this->cpv->getProduct($image->getFilename());

				if($p !== null) {
					$message = "";

					try {
						$this->model->doCreate($p, $image);
						$_SESSION[self::$sessionMessage] = "Product successfully added to the database.";
						$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
						header("Location: $actual_link");
						exit;
					}
					catch(\SQLUniqueExistsException $e) {
						$_SESSION[self::$sessionMessage] = $e->getMessage();
					}
				}
			}
		}
	}

	public function generateOutput() {
		$this->html->render($this->cpv, $this->plv);
	}
}