<?php

namespace model;

class ProductDAL {
	
	private $conn;

	/**
	 *	These names are used in database
	 *	@var string
	 */
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

	public function removeProduct($id) {
		$sql = "DELETE FROM " . self::$table . " WHERE " . self::$c_id . "=$id";
		
		if($this->conn->exec($sql) == 0) {
			throw new \PDOFetchObjectException("An error occured. Could not delete the product.");
		}					
	}

	public function updateProduct(\model\Product $p) {
		$smt = $this->conn->prepare("UPDATE ".self::$table." SET ".self::$c_title." = '".$p->getTitle()."', 
																	".self::$c_desc." = '".$p->getDesc()."', 
																	".self::$c_price." = '".$p->getPrice()."', 
																	".self::$c_unique." = '".$p->getUnique()."' WHERE ".self::$c_id."=".$p->getProductID()."");
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

		if($productArray == null) {
			throw new \PDOTableEmptyException("No products in database.");
		}
		return $productArray;
	}

	public function getProductByUnique($unique) {
		$smt = $this->conn->prepare("SELECT * FROM " . self::$table . " WHERE " . self::$c_unique . "=?");
		$smt->execute([$unique]);

		if($product = $smt->fetchObject()) {
			return new \model\Product($product->{ self::$c_title },
											$product->{ self::$c_filename },
											$product->{ self::$c_desc },
											$product->{ self::$c_price },
											$product->{ self::$c_unique },
											$product->{ self::$c_id },
											$product->{ self::$c_create },
											$product->{ self::$c_update });
		}
		throw new \PDOFetchObjectException("An error occured. Couldn't fetch the object.");
	}

	public function getProductByID($id) {
		$smt = $this->conn->prepare("SELECT * FROM " . self::$table . " WHERE " . self::$c_id . "=?");
		$smt->execute([$id]);

		if($product = $smt->fetchObject()) {
			return new \model\Product($product->{ self::$c_title },
											$product->{ self::$c_filename },
											$product->{ self::$c_desc },
											$product->{ self::$c_price },
											$product->{ self::$c_unique },
											$product->{ self::$c_id },
											$product->{ self::$c_create },
											$product->{ self::$c_update });
		}
		throw new \PDOFetchObjectException("An error occured. Couldn't fetch the object.");
	}

	public function getProductFilename($id) {	
		$smt = $this->conn->prepare("SELECT ".self::$c_filename." FROM " . self::$table . " WHERE " . self::$c_id . "=?");
		$smt->execute([$id]);
		$filename = $smt->fetchColumn();

		if($filename == null) {
			throw new \PDOFetchColumnException("An error occured. Couldn't fetch the column.");
		}
		return $filename;	
	}
}