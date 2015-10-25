<?php

namespace view;

//require_once("model/ProductModel.php");

class NavigationView {

	private static $viewURLPrefix = "p";
	private static $deleteURLPrefix = "delete";
	private static $updateURLPrefix = "update";

	public function getProductListURL() {
		return "?";
	}

	public function getProductViewURL($unique) {
		return "?".self::$viewURLPrefix."=$unique";
	}

	public function getProductDeleteURL($id) {
		return "?".self::$deleteURLPrefix."=$id";
	}

	public function getProductUpdateURL($id) {
		return "?".self::$updateURLPrefix."=$id";
	}

	public function adminWantsToViewProduct() {
		if(isset($_GET[self::$viewURLPrefix])) {
			return true;
		}
		return false;
	}

	public function adminWantsToDeleteProduct() {
		if(isset($_GET[self::$deleteURLPrefix])) {
			return true;
		}
		return false;
	}

	public function adminWantsToUpdateProduct() {
		if(isset($_GET[self::$updateURLPrefix])) {
			return true;
		}
		return false;
	}

	public function getProductUnique() {
		assert($this->adminWantsToViewProduct());
		return $_GET[self::$viewURLPrefix];
	}

	public function getProductID() {
		if($this->adminWantsToUpdateProduct()) {
			return $_GET[self::$updateURLPrefix];
		}
		if($this->adminWantsToDeleteProduct()) {
			return $_GET[self::$deleteURLPrefix];
		}
	}

	public function inProductView() {
		return isset($_GET[self::$viewURLPrefix]) == true;
	}
}