<?php

namespace model;

require_once("model/DAL/ProductDAL.php");

class ProductModel {

	private static $thumbPath = \Settings::THUMB_IMG_PATH;
	private static $thumbSide = \Settings::THUMB_IMG_SIDE;
	private static $mediumPath = \Settings::MEDIUM_IMG_PATH;
	private static $mediumSide = \Settings::MEDIUM_IMG_SIDE;

	private $conn;
	private $productDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;

		$this->productDAL = new \model\ProductDAL($this->conn);
	}

	public function doCreate(\model\Product $p, \model\Image $image) {

		
		if($this->productDAL->checkUniqueExists($p->getUnique())) {
			throw new \SQLUniqueExistsException("Unique already exists in database.");
		}
		
		$this->productDAL->addProduct($p);
		$image->uploadImage();
		$image->createSquareImage(self::$thumbSide, self::$thumbPath);
		$image->createSquareImage(self::$mediumSide, self::$mediumPath);

		return true;
	}

	public function getProducts() {
		return $this->productDAL->getProductArray();
	}

	public function getProduct($productID) {
		return $this->productDAL->getProductByID($productID);
	}
}