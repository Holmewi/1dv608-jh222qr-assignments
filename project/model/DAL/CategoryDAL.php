<?php

namespace model;

class CategoryDAL {
	
	private $conn;
	private static $table = "category";
	private static $c_name = "category_name";
	private static $c_level = "category_level";
	private static $c_id = "category_id";

	private static $p_id = "product_id";
	private static $relation_table = "product_category";


	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function checkCategoryExists($name) {
		$smt = $this->conn->prepare("SELECT " . self::$c_name . " FROM " . self::$table . " WHERE " . self::$c_name . "=?");

		if($smt->execute([$name])) {
			if($smt->fetchAll() == null) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function addCategory(\model\Category $c) {
		$smt = $this->conn->prepare("INSERT INTO " . self::$table . " (
									" . self::$c_name . ", 
									" . self::$c_level . ") 
										VALUES ('" . $c->getCategoryName() . "', 
											'" . $c->getLevel() . "')");
		$smt->execute();
	}

	public function getCategoryArray() {
		$smt = $this->conn->prepare("SELECT * FROM " . self::$table);
		$smt->execute();

		$categoryArray = array();

		while($category = $smt->fetchObject()) {
			$categoryArray[] = new \model\Category($category->{ self::$c_name },
												$category->{ self::$c_level },
												$category->{ self::$c_id });
		}

		if($categoryArray == null) {
			throw new \PDOTableEmptyException("No categories in database.");
		}
		return $categoryArray;
	}

	public function getProductCategories($id) {
		$smt = $this->conn->prepare("SELECT ".self::$table.".".self::$c_name.", ".self::$table.".".self::$c_level.", ".self::$table.".".self::$c_id." 
									FROM ".self::$table." 
									INNER JOIN ".self::$relation_table."
									ON ".self::$table.".".self::$c_id."=".self::$relation_table.".".self::$c_id." 
									WHERE ".self::$relation_table.".".self::$p_id."=$id");
		$smt->execute();

		$productCategoryArray = array();

		while($category = $smt->fetchObject()) {
			$productCategoryArray[] = new \model\Category($category->{ self::$c_name },
												$category->{ self::$c_level },
												$category->{ self::$c_id });
		}

		if($productCategoryArray == null) {
			throw new \PDOTableEmptyException("Product has no categories.");
		}
		return $productCategoryArray;
	}

	public function addProductCategory($pid, $cid) {
		$smt = $this->conn->prepare("INSERT INTO " . self::$relation_table . " (
									" . self::$p_id . ", 
									" . self::$c_id . ") 
										VALUES ('$pid', 
											'$cid')");
		$smt->execute();
	}

	public function deleteProductCategory($pid, $cid) {
		$sql = "DELETE FROM " . self::$relation_table . " WHERE " . self::$p_id . "=$pid AND " . self::$c_id . "=$cid";
		
		if($this->conn->exec($sql) == 0) {
			throw new \PDOFetchObjectException("An error occured. Could not remove the category from product.");
		}	
	}
}