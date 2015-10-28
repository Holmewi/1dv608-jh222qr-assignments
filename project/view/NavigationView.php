<?php

namespace view;

class NavigationView {

	/**
	 *	These names are used in $_GET and to render an URL
	 *	@var string
	 */
	private static $viewURLPrefix = "p";
	private static $deleteURLPrefix = "delete";
	private static $updateURLPrefix = "update";

	/**
	 *	Method to check page url if admin wants to view a product
	 *	@return boolean true | false
	 */
	public function adminWantsToViewProduct() {
		if(isset($_GET[self::$viewURLPrefix])) {
			return true;
		}
		return false;
	}

	/**
	 *	Method to check page url if admin wants to delete a product
	 *	@return boolean true | false
	 */
	public function adminWantsToDeleteProduct() {
		if(isset($_GET[self::$deleteURLPrefix])) {
			return true;
		}
		return false;
	}

	/**
	 *	Method to check page url if admin wants to update a product
	 *	@return boolean true | false
	 */
	public function adminWantsToUpdateProduct() {
		if(isset($_GET[self::$updateURLPrefix])) {
			return true;
		}
		return false;
	}

	/**
	 *	Method to check page if page is in product view
	 *	@return boolean true | false
	 */
	public function inProductView() {
		return isset($_GET[self::$viewURLPrefix]) == true;
	}

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

	
}