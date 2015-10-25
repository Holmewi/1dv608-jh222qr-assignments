<?php

namespace model;

require_once("model/DAL/ProductDAL.php");

class ProductModel {

	private static $thumbPath = \Settings::THUMB_IMG_PATH;
	private static $thumbSide = \Settings::THUMB_IMG_SIDE;
	private static $mediumPath = \Settings::MEDIUM_IMG_PATH;
	private static $mediumSide = \Settings::MEDIUM_IMG_SIDE;
	private static $largePath = \Settings::LARGE_IMG_PATH;

	private $conn;
	private $productDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;

		$this->productDAL = new \model\ProductDAL($this->conn);
	}

	public function createProduct(\model\Product $p, \model\Image $image) {

		
		if($this->productDAL->checkUniqueExists($p->getUnique())) {
			throw new \PDOUniqueExistsException("Unique already exists in database.");
		}
		
		$this->productDAL->addProduct($p);
		$image->uploadImage();
		$image->createSquareImage(self::$thumbSide, self::$thumbPath);
		$image->createSquareImage(self::$mediumSide, self::$mediumPath);

		return true;
	}

	public function deleteProduct($id) {
		$filename = $this->productDAL->getProductFilename($id);
		

		if(file_exists(self::$thumbPath . $filename)) {
			unlink(self::$thumbPath . $filename);
		}
		if(file_exists(self::$mediumPath . $filename)) {
			unlink(self::$mediumPath . $filename);
		}
		if(file_exists(self::$largePath . $filename)) {
			unlink(self::$largePath . $filename);
		}

		$this->productDAL->removeProduct($id);

		
	}

	public function updateProduct(\model\Product $p) {
		return $this->productDAL->updateProduct($p);
	}

	public function getProducts() {
		return $this->productDAL->getProductArray();
	}

	public function getProductByUnique($unique) {
		return $this->productDAL->getProductByUnique($unique);
	}

	public function getProductByID($id) {
		return $this->productDAL->getProductByID($id);
	}
}