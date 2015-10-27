<?php

namespace controller;

require_once("model/DAL/ConnectDB.php");
require_once("model/ProductModel.php");
require_once("model/CategoryModel.php");

require_once("view/NavigationView.php");

require_once("controller/AdminController.php");

class MasterController {
	
	private $model;
	private $categoryModel;
	private $nv;

	private $container;
	private $aside;

	public function __construct() {
		$connectDB = new \model\ConnectDB(\Settings::SERVER, \Settings::DATABASE, \Settings::DB_USERNAME, \Settings::DB_PASSWORD);
		$this->nv = new \view\NavigationView();

		try {
			$this->model = new \model\ProductModel($connectDB->getConnection());
			$this->categoryModel = new \model\CategoryModel($connectDB->getConnection());
		}
		catch (\DatabaseConnectionException $e) {
			echo $e->getMessage();
		}
	}

	public function doMasterControl() {
		$ac = new \controller\AdminController($this->model, $this->categoryModel, $this->nv);
		$ac->doAdminControl();
		$this->container = $ac->getContainerView();
		$this->aside = $ac->getAsideView();

		// TODO: Implement controller for the normal users
	}

	public function generateContainer() {
		return $this->container;
	}

	public function generateAside() {
		return $this->aside;
	}
}