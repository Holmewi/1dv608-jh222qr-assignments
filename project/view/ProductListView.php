<?php

namespace view;

class ProductListView {

	private static $messageID = "ProductListView::Message";
	private static $confirm = "ProductListView::Confirm";
	private static $cancel = "ProductListView::Cancel";
	private static $thumbPath = \Settings::THUMB_IMG_PATH;
	private $model;
	private $nv;

	private $message;

	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	public function __construct(\model\ProductModel $model, \view\NavigationView $nv) {
		$this->model = $model;
		$this->nv = $nv;
	}

	public function adminConfirm() {
		return isset($_POST[self::$confirm]);
	}

	public function adminCancel() {
		return isset($_POST[self::$cancel]);
	}

	public function getResponse() {
		if(empty($this->message)) {
			$this->message = $this->getSessionMessage();
		}
		return $this->getHTML();
	}

	private function getSessionMessage() {
		if (isset($_SESSION[self::$sessionMessage])) {
			$message = $_SESSION[self::$sessionMessage];
			unset($_SESSION[self::$sessionMessage]);
			return $message;
		}
		return "";
	}

	public function getHTML() {
		$productArray = $this->getProductArray();

		$ret = "<ul>
					<li class='list-header'>
						<span class='product-id'>ID</span>
						<span class='title'>Title</span>
						<span class='price'>Price</span>
						<span class='create-date'>Created Date</span>
						<span class='update-date'>Last Updated</span>
					</li>";

		if($productArray !== null) {
			foreach ($productArray as $product) {
							
				$title = $product->getTitle();
				$filename = $product->getFilename();
				$desc = $product->getDesc();
				$price = $product->getPrice();
				$unique = $product->getUnique();
				$productID = $product->getProductID();
				$createDatetime = date("Y/m/d H:m", strtotime($product->getCreateDatetime()));
				$updateDatetime = "-";
				$viewURL = $this->nv->getProductViewURL($unique);
				$idURL = $this->nv->getProductIDURL($productID);

				if($product->getUpdateDatetime() != null) {
					$updateDatetime = date("Y/m/d H:m", strtotime($product->getUpdateDatetime()));
				}
				
				$ret .= "<li class='list-product'>
							<span class='thumb-col'><img src='".self::$thumbPath.$filename."' class='thumb-img'></span>
							<span class='product-id'>$productID</span>
							<span class='title'><a href='$viewURL'>$title</a></span> 
							<span class='price'>$price</span>
							<span class='create-date'>$createDatetime</span>
							<span class='update-date'>$updateDatetime</span>
							<span class='delete'><a href='$idURL'>Delete</a></span>
						</li>".$this->getDeleteConfirmation($productID)."";
			}
		}

		$ret .= "</ul><p id='".self::$messageID."'>".$this->message."</p>";

		return "<h3>Product List</h3> $ret";
	}

	private function getProductArray() {
		try{
			return $this->model->getProducts();
		}
		catch(\PDOTableEmptyException $e) {
			$this->message = $e->getMessage();
		}
	}

	public function getSelectedProduct() {
		assert($this->nv->adminWantsToViewProduct());
		$unique = $this->nv->getProductUnique();

		try {
			return $this->model->getProductByUnique($unique);
		} 
		catch(\PDOFetchObjectException $e) {
			$this->message = $e->getMessage();
		}
	}

	public function getDeleteConfirmation($id) {
		if($this->nv->adminWantsToDeleteProduct() && $this->nv->getProductID() == $id) {
			return "<li class='delete-confirm'><span>Are you sure you want to delete this product?</span>
						<form method='post'>
							<input type='submit' name='".self::$cancel."' value='Cancel' class='cancel-button'/>
							<input type='submit' name='".self::$confirm."' value='Delete' class='confirm-button'/>
						</form>
					</li>";
		}
	}
}