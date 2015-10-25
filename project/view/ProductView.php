<?php

namespace view;

class ProductView {

	private static $mediumPath = \Settings::MEDIUM_IMG_PATH;
	private $nv;
	private $product;

	public function __construct(\view\NavigationView $nv, $product) {
		$this->nv = $nv;
		$this->product = $product;
	}

	public function getResponse() {
		/*if(empty($this->message)) {
			$this->message = $this->getSessionMessage();
		}*/
		return $this->getHTML();
	}

	public function getHTML() {
		return "<div class'product'><img src='".self::$mediumPath.$this->product->getFilename()."' class='medium-img'>
			<div class='product-info'><h3>".$this->product->getTitle()."</h3>
			<p>ID: ".$this->product->getProductID()."</p>
			<p>".$this->product->getDesc()."</p>
			<p>Price: ".$this->product->getPrice()." SEK</p>
			<p>First created: ".$this->product->getCreateDatetime()."</p>
			<p>Last updated: ".$this->product->getUpdateDatetime()."</p></div></div>
			<div class='product-action'><ul>
				<li><a href='".$this->nv->getProductListURL()."'>Back to list</a></li>
				<li>Update</li>
				<li>Delete</li>
			</ul></div>";
	}
}