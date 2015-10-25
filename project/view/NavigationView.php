<?php

namespace view;

//require_once("model/ProductModel.php");

class NavigationView {

	private static $viewURLPrefix = "p";
	private static $idURLPrefix = "id";

	public function getProductListURL() {
		return "?";
	}

	public function getProductViewURL($unique) {
		return "?".self::$viewURLPrefix."=$unique";
	}

	public function getProductIDURL($id) {
		return "?".self::$idURLPrefix."=$id";
	}

	public function adminWantsToViewProduct() {
		if(isset($_GET[self::$viewURLPrefix])) {
			return true;
		}
		return false;
	}

	public function adminWantsToDeleteProduct() {
		if(isset($_GET[self::$idURLPrefix])) {
			return true;
		}
		return false;
	}

	public function getProductUnique() {
		assert($this->adminWantsToViewProduct());
		return $_GET[self::$viewURLPrefix];
	}

	public function getProductID() {
		assert($this->adminWantsToDeleteProduct());
		return $_GET[self::$idURLPrefix];
	}

	public function inProductView() {
		return isset($_GET[self::$viewURLPrefix]) == true;
	}

	/*
	public function getSelectedProduct(\model\ProductModel $model) {
		assert($this->adminWantsToViewProduct());
		$unique = $this->getProductUnique();

		try {
			return $model->getProduct($unique);
		} 
		catch(\PDOFetchObjectException $e) {
			$this->message = $e->getMessage();
		}
	}
	*/
}