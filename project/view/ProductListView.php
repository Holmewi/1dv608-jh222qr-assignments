<?php

namespace view;

class ProductListView {

	/**
	 *	These names are used in $_POST
	 *	@var string
	 */
	private static $messageID = "ProductListView::Message";
	private static $confirm = "ProductListView::Confirm";
	private static $cancel = "ProductListView::Cancel";
	private static $title = "ProductListView::Title";
	private static $price = "ProductListView::Price";

	/**
	 *	Settings for path of images
	 *	@var string
	 */
	private static $thumbPath = \Settings::THUMB_IMG_PATH;

	/**
	 * 	This name is used in session
	 * 	@var string
	 */
	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;
	

	private $model;
	private $nv;
	private $message;
	
	public function __construct(\model\AdminModel $model, \view\NavigationView $nv) {
		$this->model = $model;
		$this->nv = $nv;
	}

	/**
	 * Method to check if admin confirms delete or edit of product
	 * @return boolean true if admin tried to confirm
	 */
	public function adminConfirm() {
		if(isset($_POST[self::$confirm])) {
			return isset($_POST[self::$confirm]);
		}
	}

	/**
	 * Method to check if admin cancel delete or edit of product
	 * @return boolean true if admin tried to cancel
	 */
	public function adminCancel() {
		if(isset($_POST[self::$cancel])) {
			return isset($_POST[self::$cancel]);
		}
	}

	/**
	 * Gets the response of the class state
	 * @return string HTML
	 */
	public function getResponse() {
		if(empty($this->message)) {
			$this->message = $this->getSessionMessage();
		}
		return $this->getHTML();
	}

	/**
	 * Create HTTP response and redirect the page to load a message from another class
	 */
	public function redirect($message) {
		$_SESSION[self::$sessionMessage] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("Location: $actual_link");
		exit;
	}

	/**
	 * Sets the $message to the session stored string of isset
	 * @return string $message or empty
	 */
	private function getSessionMessage() {
		if (isset($_SESSION[self::$sessionMessage])) {
			$message = $_SESSION[self::$sessionMessage];
			unset($_SESSION[self::$sessionMessage]);
			return $message;
		}
		return "";
	}

	/**
	 * 	Method to generate HTML
	 * 	@return string HTML
	 */
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
				$createDatetime = date("Y/m/d H:i", strtotime($product->getCreateDatetime()));
				$updateDatetime = "-";
				$viewURL = $this->nv->getProductViewURL($unique);
				$deleteURL = $this->nv->getProductDeleteURL($productID);
				$updateURL = $this->nv->getProductUpdateURL($productID);

				if($product->getUpdateDatetime() !== null) {
					$updateDatetime = date("Y/m/d H:i", strtotime($product->getUpdateDatetime()));
				}
				
				$ret .= "<li class='list-product'>
							<span class='thumb-col'><img src='".self::$thumbPath.$filename."' class='thumb-img'></span>
							<span class='product-id'>$productID</span>
							<span class='title'><a href='$viewURL'>$title</a></span> 
							<span class='price'>$price</span>
							<span class='create-date'>$createDatetime</span>
							<span class='update-date'>$updateDatetime</span>
							<span class='edit-list-action'><a href='$updateURL'>Edit</a></span>
							<span class='delete-list-action'><a href='$deleteURL'>Delete</a></span>
						</li>".$this->getDeleteConfirmation($productID)."".$this->getUpdateForm($product)."";
			}
		}

		$ret .= "</ul>";

		return "<span class='header-message'><h3>Product List</h3><p id='".self::$messageID."'>".$this->message."</p></span>$ret";
	}

	/**
	 * 	Method to generate HTML
	 *	@param int $id
	 * 	@return string HTML
	 */
	public function getDeleteConfirmation($id) {
		if($this->nv->adminWantsToDeleteProduct() && $this->nv->getProductID() == $id) {
			return "<li class='delete-form'><span>Are you sure you want to delete this product?</span>
						<form method='post'>
							<input type='submit' name='".self::$cancel."' value='Cancel' class='cancel-button'/>
							<input type='submit' name='".self::$confirm."' value='Delete' class='confirm-button'/>
						</form>
					</li>";
		}
	}

	/**
	 * 	Method to generate HTML
	 *	@param \model\Product $p
	 * 	@return string HTML
	 */
	public function getUpdateForm(\model\Product $p) {
		if($this->nv->adminWantsToUpdateProduct() && $this->nv->getProductID() == $p->getProductID()) {
			return "<li class='update-form'>
						<form method='post'>
							<input type='text' class='update-title-input' id='".self::$title."' name='".self::$title."' value='".$p->getTitle()."'/>
							<input type='text' class='update-price-input' id='".self::$price."' name='".self::$price."' value='".$p->getPrice()."'/>
							
							<input type='submit' name='".self::$cancel."' value='Cancel' class='cancel-button'/>
							<input type='submit' name='".self::$confirm."' value='Update' class='confirm-button'/>
						</form>
					</li>";
		}
	}

	/**
	 * 	Method to get new product from old product
	 *	@param \model\Product $p
	 * 	@return new \model\Product()
	 */
	public function getUpdatedProduct(\model\Product $p) {
		$this->message = "";
			
		try {
			return new \model\Product($this->getTitle(), 
										$p->getFilename(), 
										$p->getDesc(), 
										$this->getPrice(),
										$p->getUnique(),
										$p->getProductID());		
		}
		catch(\TitleMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\TitleWrongLengthException $e) {
			$this->message = $e->getMessage();
		}
		catch(\DescMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\DescWrongLengthException $e) {
			$this->message = $e->getMessage();
		}
		catch(\PriceWrongFormatException $e) {
			$this->message = $e->getMessage();
		}
		catch(\PriceTooLowException $e) {
			$this->message = $e->getMessage();
		}
		catch(\PriceMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\UniqueMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\UniqueURLException $e) {
			$this->message = $e->getMessage();
		}
		catch(\Exception $e) {
			$this->message = $e->getMessage();
		}
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
	
	private function getTitle() {
		if (isset($_POST[self::$title]))
			return trim($_POST[self::$title]);
	}

	private function getPrice() {
		if (isset($_POST[self::$price]))
			return trim($_POST[self::$price]);
	}
}