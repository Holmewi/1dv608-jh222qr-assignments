<?php

namespace controller;

require_once("model/DAL/ConnectDB.php");
require_once("model/AdminModel.php");
require_once("view/NavigationView.php");
require_once("controller/AdminController.php");

class MasterController {
	
	private $adminModel;
	private $nv;

	/**
	 * These fields are views that renders to the HTMLView
	 */
	private $container;
	private $aside;

	public function __construct() {
		$connectDB = new \model\ConnectDB(\Settings::SERVER, \Settings::DATABASE, \Settings::DB_USERNAME, \Settings::DB_PASSWORD);
		$this->nv = new \view\NavigationView();

		try {
			$this->adminModel = new \model\AdminModel($connectDB->getConnection());
		}
		catch (\DatabaseConnectionException $e) {
			echo $e->getMessage();
		}
	}

	public function doMasterControl() {
		$ac = new \controller\AdminController($this->adminModel, $this->nv);
		$ac->doAdminControl();

		$this->container = $ac->getContainerView();
		$this->aside = $ac->getAsideView();

		// TODO: Implement controller for the normal users
	}

	/**
	 *	@return view
	 */
	public function generateContainer() {
		return $this->container;
	}

	/**
	 *	@return view
	 */
	public function generateAside() {
		return $this->aside;
	}
}