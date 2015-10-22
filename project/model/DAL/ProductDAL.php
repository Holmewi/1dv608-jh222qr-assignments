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

	public function createProduct(\model\Product $p) {
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
}