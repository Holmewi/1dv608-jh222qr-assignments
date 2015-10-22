<?php

namespace model;

class Product {
	private $title;
	private $filename;
	private $desc;
	private $price;
	private $unique;

	public function __construct($title, $filename, $desc, $price, $unique) {

		
		if(empty($title)) {
			throw new \TitleMissingException("Title is missing, you need to enter a title.");
		}
		if(strlen($title) < 3 || strlen($title) > 64) {
			throw new \TitleWrongLengthException("Title must be between 2 and 64 characters.");
		}
		if(empty($desc)) {
			throw new \DescMissingException("Description is missing, you need to enter a description.");
		}
		if(strlen($desc) < 3 || strlen($desc) > 1024) {
			throw new \DescWrongLengthException("Description must be between 2 and 1024 characters.");
		}
		if($price == null) {
			throw new \PriceMissingException("Price is missing, you need to enter a price.");
		}
		if(!is_numeric($price)) {
			throw new \PriceWrongFormatException("Price must be a numberic value.");	
		}
		if($price <= 0) {
			throw new \PriceTooLowException("Price must be bigger than 0.");
		}
		if(empty($unique)) {
			throw new \UniqueMissingException("Unique is missing, you need to enter an unique url string.");
		}
		if(urlencode($unique) != $unique) {
			throw new \UniqueURLException("Unique needs to be an URL string format.");
		}
		
		$this->title = $title;
		$this->filename = $filename;
		$this->desc = $desc;
		$this->price = $price;
		$this->unique = $unique;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getFilename() {
		return $this->filename;
	}

	public function getDesc() {
		return $this->desc;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getUnique() {
		return $this->unique;
	}
}