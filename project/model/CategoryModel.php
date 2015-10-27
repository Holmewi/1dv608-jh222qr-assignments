<?php

namespace model;

require_once("model/DAL/CategoryDAL.php");

class CategoryModel {
	
	private $conn;
	private $categoryDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
		$this->categoryDAL = new \model\CategoryDAL($this->conn);
	}

	public function createCategory(\model\Category $c) {

		if($this->categoryDAL->checkCategoryExists($c->getCategoryName())) {
			throw new \PDOCategoryExistsException("Category name already exists in database.");
		}
		
		$this->categoryDAL->addCategory($c);

		return true;
	}

	public function getAllCategories() {
		return $this->categoryDAL->getCategoryArray();
	}

	public function getProductCategories($id) {
		return $this->categoryDAL->getProductCategories($id);
	}

	public function addProductCategory($pid, $cid) {
		return $this->categoryDAL->addProductCategory($pid, $cid);
	}

	public function deleteProductCategory($pid, $cid) {
		return $this->categoryDAL->deleteProductCategory($pid, $cid);
	}
}