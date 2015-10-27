<?php

namespace view;

class ProductView {

	private static $messageID = "ProductView::Message";
	private static $title = "ProductView::Title";
	private static $desc = "ProductView::Desc";
	private static $price = "ProductView::Price";
	private static $unique = "ProductView::Unique";

	private static $editTitle = "ProductView::EditTitle";
	private static $editDesc = "ProductView::EditDesc";
	private static $editPrice = "ProductView::EditPrice";
	private static $editUnique = "ProductView::EditUnique";
	private static $delete = "ProductView::Delete";
	private static $edit = "ProductView::Edit";

	private static $confirm = "ProductView::Confirm";
	private static $cancel = "ProductView::Cancel";
	private static $confirmDelete = "ProductListView::ConfirmDelete";
	private static $cancelDelete = "ProductListView::CancelDelete";

	private static $mediumPath = \Settings::MEDIUM_IMG_PATH;
	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	private $nv;
	private $product;
	private $message;

	public function __construct(\view\NavigationView $nv, \model\Product $product) {
		$this->nv = $nv;
		$this->product = $product;
	}

	public function adminConfirm() {
		if(isset($_POST[self::$confirm])) {
			return isset($_POST[self::$confirm]);
		}
	}

	public function adminCancel() {
		if(isset($_POST[self::$cancel])) {
			return isset($_POST[self::$cancel]);
		}
	}

	public function adminConfirmDelete() {
		if(isset($_POST[self::$confirmDelete])) {
			return isset($_POST[self::$confirmDelete]);
		}
	}

	public function adminCancelDelete() {
		if(isset($_POST[self::$cancelDelete])) {
			return isset($_POST[self::$cancelDelete]);
		}
	}

	public function getResponse() {
		if(empty($this->message)) {
			$this->message = $this->getSessionMessage();
		}
		return $this->getHTML();
	}

	public function redirect($message) {
		$_SESSION[self::$sessionMessage] = $message;
		$actual_link = $this->nv->getProductViewURL($this->getUnique());
		header("Location: $actual_link");
		exit;
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
		//var_dump($this->product);
		$createDatetime = date("Y/m/d H:i", strtotime($this->product->getCreateDatetime()));
		$updateDatetime = "-";

		if($this->product->getUpdateDatetime() !== null) {
			$updateDatetime = date("Y/m/d H:i", strtotime($this->product->getUpdateDatetime()));
		}

		return "<h3>Product</h3><div class='product-action'>
					<span class='back-message'>
						<p class='back-link'><a href='".$this->nv->getProductListURL()."'>Back to product list</a></p>
						<p id='".self::$messageID."' class='product-view-message'>".$this->message."</p>
					</span>
						".$this->getDeleteConfirmation(self::$delete)."
					
				</div>
				<div class='product'><img src='".self::$mediumPath.$this->product->getFilename()."' class='medium-img'>
					<div class='product-info'>
						<div class='column-row'><span class='column-row-title'><p>Product ID:</p></span><p class='column-noedit'>".$this->product->getProductID()."</p></div>
						<div class='column-row'>".$this->getUpdateFormField("Title:", $this->product->getTitle(), self::$editTitle, self::$title)."</div>
						<div class='column-row'>".$this->getUpdateFormField("Description:", $this->product->getDesc(), self::$editDesc, self::$desc)."</div>
						<div class='column-row'>".$this->getUpdateFormField("Price:", $this->product->getPrice(), self::$editPrice, self::$price)."</div>
						<div class='column-row'>".$this->getUpdateFormField("Unique:", $this->product->getUnique(), self::$editUnique, self::$unique)."</div>
						<div class='column-row'><span class='column-row-title'><p>First created:</p></span><p class='column-noedit'>$createDatetime</p></div>
						<div class='column-row'><span class='column-row-title'><p>Last updated:</p></span><p class='column-noedit'>$updateDatetime</p></div>
					</div>
				</div>";
	}

	private function getUpdateFormField($title, $column, $state, $input) {
		if($this->nv->inProductView()) {
			if($input == self::$desc) {
				$retColumn = "<span class='column-desc'><p>$column</p></span>";
				$retColumnForm = "<textarea id='$input' name='$input'>$column</textarea>";
			} else {
				$retColumn = "<p>$column</p>";
				$retColumnForm = "<input type='text' id='$input' name='$input' value='$column'/>";
			}

			if(!isset($_POST[$state])) {
				return "<span class='column-row-title'><p>$title</p></span>
						<form method='post' class='edit-submit'><input type='submit' name='$state' value='Edit'/></form>$retColumn";
			}
			else if(isset($_POST[$state])) {
				return "<form method='post'>
							<span class='column-row-title'><p>$title</p></span>
							<div class='update-action'>
							$retColumnForm
								<div class='update-confirm'>
									<input type='submit' name='".self::$cancel."' value='Cancel' class='cancel-button'/>
									<input type='submit' name='".self::$confirm."' value='Update' class='confirm-button'/>
								</div>
							</div>
						</form>";
			}
		}
	}

	private function getDeleteConfirmation($state) {
		if(!isset($_POST[$state])) {
			return "<form method='post' class='delete-submit'><input type='submit' name='".self::$delete."' value='Delete Product'/></form>";
		} else {
			return "<form method='post' class='delete-submit'>
						<span>Are you sure you want to delete this product?</span>
						<input type='submit' name='".self::$cancelDelete."' value='Cancel' class='cancel-button'/>
						<input type='submit' name='".self::$confirmDelete."' value='Delete' class='confirm-button'/>
					</form>";
		}
	}

	public function getUpdatedProduct() {
		$this->message = "";
			
		try {
			return new \model\Product($this->getTitle(), 
										$this->getFilename(), 
										$this->getDesc(), 
										$this->getPrice(),
										$this->getUnique(),
										$this->product->getProductID());		
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

	private function getSEOStringURL ($string) {
		$string = strtolower($string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		$string = preg_replace("/[\s-]+/", " ", $string);
		$string = preg_replace("/[\s_]/", "-", $string);

    	return $string;
	}

	private function getTitle() {
		if (isset($_POST[self::$title])) {
			return trim($_POST[self::$title]);
		}
		return $this->product->getTitle();
	}

	private function getFilename() {
		return $this->product->getFilename();
	}

	private function getDesc() {
		if (isset($_POST[self::$desc])) {
			return trim($_POST[self::$desc]);
		}
		return $this->product->getDesc();
	}

	private function getPrice() {
		if (isset($_POST[self::$price])) {
			return trim($_POST[self::$price]);
		}
		return $this->product->getPrice();
	}

	private function getUnique() {
		if (isset($_POST[self::$unique])) {
			return $this->getSEOStringURL(trim($_POST[self::$unique]));
		}
		return $this->product->getUnique();
	}

	public function getProductID() {
		return $this->product->getProductID();
	}
}