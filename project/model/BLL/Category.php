<?php

namespace model;

class Category {
	
	private $categoryName;
	private $level;
	private $categoryID;

	public function __construct($categoryName, $level, $categoryID = null) {

		if(empty($categoryName)) {
			throw new \CategoryMissingException("Category name is missing, you need to enter a category name.");
		}
		if(strlen($categoryName) < 2 || strlen($categoryName) > 12) {
			throw new \CategoryWrongLengthException("Category name must be between 2 and 12 characters.");
		}
		if(urlencode($categoryName) != $categoryName) {
			throw new \CategoryWrongLengthException("Category name needs to be an URL string format.");
		}
		
		$this->categoryName = str_replace('&amp;', '&', htmlspecialchars($categoryName));
		$this->level = $level;
		$this->categoryID = $categoryID;
	}

	public function getCategoryName() {
		return $this->categoryName;
	}

	public function getLevel() {
		return $this->level;
	}

	public function getCategoryID() {
		return $this->categoryID;
	}
}