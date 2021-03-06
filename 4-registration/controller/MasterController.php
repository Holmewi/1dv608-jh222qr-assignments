<?php

namespace controller;

require_once("model/RegisterModel.php");
require_once("model/LoginModel.php");

require_once("view/NavigationView.php");
require_once("view/DateTimeView.php");
require_once("view/LayoutView.php");
require_once("view/LoginView.php");
require_once("view/RegisterView.php");

require_once("controller/RegisterController.php");
require_once("controller/LoginController.php");

class MasterController {
	private $connection;
	private $v_nv;
	private $m_lm;
	private $v_lv;
	private $m_rm;
	private $v_rv;
	
	public function __construct() {
		$this->connection = new \model\Connect(\Settings::SERVER, 
												\Settings::DATABASE,
												\Settings::DB_USERNAME,
												\Settings::DB_PASSWORD);
		$this->v_nv = new \view\NavigationView();
		$this->m_lm = new \model\LoginModel($this->connection->doConnect());
		$this->v_lv = new \view\LoginView($this->m_lm, $this->v_nv);
		$this->m_rm = new \model\RegisterModel($this->connection->doConnect());
		$this->v_rv = new \view\RegisterView($this->m_rm, $this->v_nv, $this->v_lv);
	}
	
	public function doMasterControl() {
		if($this->v_nv->inRegistration()) {
			$rc = new \controller\RegisterController($this->m_rm, $this->v_rv);
			$rc->doRegisterControl();
		} else {
			$lc = new \controller\LoginController($this->m_lm, $this->v_lv);
			$lc->doLoginControl();
		}
	}

	public function generateOutput() {
		$dtv = new \view\DateTimeView();
		$htmlv = new \view\LayoutView();
		$htmlv->render($this->m_lm->isLoggedIn($this->v_lv->getUserClient()), 
								$this->v_lv, 
								$this->v_rv, 
								$dtv,
								$this->v_nv);
	}
}