<?php

namespace model;

require_once("model/DAL/ProductDAL.php");
require_once("model/DAL/ImageDAL.php");

class ProductModel {

	private $conn;
	private $productDAL;
	private $imageDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;

		$this->productDAL = new \model\ProductDAL($this->conn);
		$this->imageDAL = new \model\ImageDAL();
	}

	public function doCreate(\model\Product $p, \model\Image $image) {

		
		if($this->productDAL->checkUniqueExists($p->getUnique())) {
			throw new \SQLUniqueExistsException("Unique already exists in database.");
		}
		
		$this->productDAL->createProduct($p);
		$this->imageDAL->uploadImage($image);

		return true;
	}
}