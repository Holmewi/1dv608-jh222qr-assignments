<?php 

namespace controller;

require_once("model/BLL/Image.php");
require_once("model/BLL/Product.php");
require_once("model/ProductModel.php");
require_once("view/HTMLView.php");
require_once("view/CreateProductView.php");

class AdminController {

	private $connect;
	private $model;
	private $html;
	private $cpv;

	public function __construct() {
		$this->connect = new \model\ConnectDB(\Settings::SERVER, 
												\Settings::DATABASE,
												\Settings::DB_USERNAME,
												\Settings::DB_PASSWORD);
		
		try {
			$this->model = new \model\ProductModel($this->connect->getConnection());
		}
		catch (\DatabaseConnectionException $e) {
			var_dump("Database connection failed");
		}

		$this->html = new \view\HTMLView();
		$this->cpv = new \view\CreateProductView();
	}

	public function doControl() {
		if($this->cpv->adminWantsToAddProduct()) {
			$image = $this->cpv->getImage();
			$p = $this->cpv->getProduct($image->getFilename());

			if($p !== null) {
				$message = "";

				try {
					$this->model->doCreate($p, $image);
				}
				catch(\SQLUniqueExistsException $e) {
					$message = "Unique already exists in database.";
				}

				var_dump($message);
				/*
				if($this->model->doCreate($p)) {
					//TODO: Set success status
					header("Location: index.php");
					exit;
				} else {
					//TODO: Set fail status
				}*/
			}
		}
	}

	public function generateOutput() {
		$this->html->render($this->cpv);
	}
}