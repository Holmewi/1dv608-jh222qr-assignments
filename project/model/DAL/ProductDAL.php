<?php

namespace model;

class ProductDAL {
	
	private $conn;
	private static $table = "product";
	private static $c_title = "title";
	private static $c_filename = "filename";
	private static $c_desc = "description";
	private static $c_price = "price";
	private static $c_unique = "unique_string";
	private static $c_id = "product_id";
	private static $c_create = "created";
	private static $c_update = "updated";

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function checkUniqueExists($unique) {
		$smt = $this->conn->prepare("SELECT " . self::$c_unique . " FROM " . self::$table . " WHERE " . self::$c_unique . "=?");

		if($smt->execute([$unique])) {
			if($smt->fetchAll() == null) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function addProduct(\model\Product $p) {
		$smt = $this->conn->prepare("INSERT INTO " . self::$table . " (
									" . self::$c_title . ", 
									" . self::$c_filename . ", 
									" . self::$c_desc . ",
									" . self::$c_price . ",
									" . self::$c_unique . ") 
										VALUES ('" . $p->getTitle() . "', 
											'" . $p->getFilename() . "',
											'" . $p->getDesc() . "',
											'" . $p->getPrice() . "',
											'" . $p->getUnique() . "')");
		$smt->execute();
	}

	public function getProductArray() {
		$smt = $this->conn->prepare("SELECT * FROM " . self::$table);
		$smt->execute();

		$productArray = array();

		while($product = $smt->fetchObject()) {
			$productArray[] = new \model\Product($product->{ self::$c_title },
												$product->{ self::$c_filename },
												$product->{ self::$c_desc },
												$product->{ self::$c_price },
												$product->{ self::$c_unique },
												$product->{ self::$c_id },
												$product->{ self::$c_create },
												$product->{ self::$c_update });
		}

		return $productArray;
	}

	public function getProductByID($productID) {
		// TOTO: Implement
		return null;
	}
}