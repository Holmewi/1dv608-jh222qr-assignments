<?php

namespace model;

require_once("model/DAL/ProductDAL.php");
require_once("model/DAL/CategoryDAL.php");

class AdminModel {
	/**
	 *	Settings for saving images: side size and path
	 *	@var int
	 *	@var string
	 */
	private static $thumbPath = \Settings::THUMB_IMG_PATH;
	private static $thumbSide = \Settings::THUMB_IMG_SIDE;
	private static $mediumPath = \Settings::MEDIUM_IMG_PATH;
	private static $mediumSide = \Settings::MEDIUM_IMG_SIDE;
	private static $largePath = \Settings::LARGE_IMG_PATH;

	private $conn;
	private $productDAL;
	private $categoryDAL;

	/**
	 * @param \PDO $conn
	 */
	public function __construct(\PDO $conn) {
		$this->conn = $conn;
		$this->productDAL = new \model\ProductDAL($this->conn);
		$this->categoryDAL = new \model\CategoryDAL($this->conn);
	}

	/**
	 *	Method for creating a product
	 *	@param \model\Product $p
	 *	@param \model\Image $image
	 */
	public function createProduct(\model\Product $p, \model\Image $image) {
		
		if($this->productDAL->checkUniqueExists($p->getUnique())) {
			throw new \PDOUniqueExistsException("Unique already exists in database.");
		}
		
		$this->productDAL->addProduct($p);
		$image->uploadImage();
		$image->createSquareImage(self::$thumbSide, self::$thumbPath);
		$image->createSquareImage(self::$mediumSide, self::$mediumPath);
	}

	/**
	 *	Method for deleting a product
	 *	@param int $id
	 */
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

	/**
	 *	Method for updating a product
	 *	@param \model\Product $p
	 */
	public function updateProduct(\model\Product $p) {
		return $this->productDAL->updateProduct($p);
	}

	/**
	 *	Method for getting all products
	 * 	@return array
	 */
	public function getProducts() {
		return $this->productDAL->getProductArray();
	}

	/**
	 *	Method for getting a product by unique
	 * 	@param string $unique
	 * 	@return \model\Product object 
	 */
	public function getProductByUnique($unique) {
		return $this->productDAL->getProductByUnique($unique);
	}

	/**
	 *	Method for getting a product by id
	 * 	@param int $id
	 * 	@return \model\Product object 
	 */
	public function getProductByID($id) {
		return $this->productDAL->getProductByID($id);
	}

	/**
	 *	Method for creating a category
	 *	@param \model\Category $c
	 */
	public function createCategory(\model\Category $c) {

		if($this->categoryDAL->checkCategoryExists($c->getCategoryName())) {
			throw new \PDOCategoryExistsException("Category name already exists in database.");
		}
		
		$this->categoryDAL->addCategory($c);
	}

	/**
	 *	Method for getting all categories
	 *	@return array
	 */
	public function getAllCategories() {
		return $this->categoryDAL->getCategoryArray();
	}

	/**
	 *	Method for getting a category by id
	 *	@param int $id
	 *	@return \model\Category object
	 */
	public function getProductCategories($id) {
		return $this->categoryDAL->getProductCategories($id);
	}

	/**
	 *	Method for adding a category by id to a product by id
	 *	@param int $pid
	 *	@param int $cid
	 */
	public function addProductCategory($pid, $cid) {
		$this->categoryDAL->addProductCategory($pid, $cid);
	}

	/**
	 *	Method for deleting a category by id from a product by id
	 *	@param int $pid
	 *	@param int $cid
	 */
	public function deleteProductCategory($pid, $cid) {
		$this->categoryDAL->deleteProductCategory($pid, $cid);
	}
}