<?php

namespace view;

class ProductListView {

	private static $thumbPath = \Settings::THUMB_IMG_PATH;
	private $model;

	public function __construct(\model\ProductModel $model) {
		$this->model = $model;
	}

	public function getHTML() {
		$productArray = $this->model->getProducts();

		$ret = "<ul>
					<li class='list-header'>
						<span class='product-id'>ID</span>
						<span class='title'>Title</span>
						<span class='price'>Price</span>
						<span class='create-date'>Created Date</span>
						<span class='update-date'>Last Updated</span>
					</li>";

		foreach ($productArray as $product) {
						
			$title = $product->getTitle();
			$filename = $product->getFilename();
			$desc = $product->getDesc();
			$price = $product->getPrice();
			$unique = $product->getUnique();
			$productID = $product->getProductID();
			$createDatetime = date("Y/m/d H:m", strtotime($product->getCreateDatetime()));
			$updateDatetime = null;
			if($product->getUpdateDatetime() != null) {
				$updateDatetime = date("Y/m/d H:m", strtotime($product->getUpdateDatetime()));
			}
			
			$ret .= "<li class='list-product'>
						<span class='thumb-col'><img src='".self::$thumbPath.$filename."' class='thumb-img'></span>
						<span class='product-id'>$productID</span>
						<span class='title'><a href='#'>$title</a></span> 
						<span class='price'>$price</span>
						<span class='create-date'>$createDatetime</span>
						<span class='update-date'>$updateDatetime</span>
					</li>";
		}

		$ret .= "</ul>";

		return "<h3>Product List</h3> $ret";
	}
}