<?php

namespace view;

class HandleCategoryView {
	
	private static $messageID = "HandleCategoryView::Message";
	private static $update = "HandleCategoryView::Update";
	private static $create = "HandleCategoryView::Create";
	private static $confirm = "HandleCategoryView::Confirm";
	private static $cancel = "HandleCategoryView::Cancel";
	private static $category = "HandleCategoryView::Category";
	private static $level = "HandleCategoryView::Level";
	private static $check = "check";

	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	private $message;
	private $model;
	private $nv;
	private $product;

	// The products category list
	private $productCategoryArray = array();
	// Categories to delete
	private $deleteCategoryArray = array();
	// Categories to add
	private $addCategoryArray = array();

	public function __construct(\view\NavigationView $nv, \model\CategoryModel $model, \model\Product $product) {
		$this->nv = $nv;
		$this->model = $model;
		$this->product = $product;	
	}

	public function adminWantsToUpdateCategories() {
		return isset($_POST[self::$update]);
	}

	public function adminWantsToCreateCategory() {
		return isset($_POST[self::$confirm]);
	}

	public function getResponse() {
		if(empty($this->message)) {
			$this->message = $this->getSessionMessage();
		}
		return $this->getHTML();
	}

	public function redirect($message) {
		$_SESSION[self::$sessionMessage] = $message;
		$actual_link = $this->nv->getProductViewURL($this->product->getUnique());
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

	public function getCategory() {
		$this->message = "";

		try {
			return new \model\Category($this->getSEOStringURL($this->getCategoryName()), $this->getLevel());		
		}
		catch(\CategoryMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\CategoryWrongLengthException $e) {
			$this->message = $e->getMessage();
		}
		catch(\CategoryWrongLengthException $e) {
			$this->message = $e->getMessage();
		}
		catch(\Exception $e) {
			$this->message = $e->getMessage();
		}
	}

	private function getHTML() {
		$categoryArray = $this->getCategoryArray();	

		$ret = "".$this->getCreateCategoryForm()."
				<div class='add-category-form'><form method='post'> 
					<fieldset>
						<legend><h5>Edit product categories</h5></legend>";

		if (count($categoryArray) !== null) {
			$ret .= "<div class='primary-categories'>
							<p class='category-level'>Primary categories</p>";

			foreach ($categoryArray as $c) {
				$name = $c->getCategoryName();
				$level = $c->getLevel();
				$cid = $c->getCategoryID();
				if($level == 1) {
					$ret .= "<div class='checkbox'><input type='checkbox' name='".self::$check."[]' value='$cid' ".$this->getCheck($cid)."> <span>$name</span></div>";
				}
			}
			$ret .= "</div>";

			$ret .= "<div class='secondary-categories'>
							<p class='category-level'>Secondary categories</p>";

			foreach ($categoryArray as $c) {
				$name = $c->getCategoryName();
				$level = $c->getLevel();
				$cid = $c->getCategoryID();
				if($level == 2) {
					$ret .= "<div class='checkbox'><input type='checkbox' name='".self::$check."[]' value='$cid' ".$this->getCheck($cid)."> <span>$name</span></div>";
				}
			}
			$ret .= "</div>";

			$ret .= "<div class='tertiary-categories'>
							<p class='category-level'>Tertiary categories</p>";

			foreach ($categoryArray as $c) {
				$name = $c->getCategoryName();
				$level = $c->getLevel();
				$cid = $c->getCategoryID();
				if($level == 3) {
					$ret .= "<div class='checkbox'><input type='checkbox' name='".self::$check."[]' value='$cid' ".$this->getCheck($cid)."> <span>$name</span></div>";
				}
			}
			$ret .= "</div>";
		}

		$ret .= "<input class='submit-button' type='submit' name='".self::$update."' value='Update Product Category'/>
					</fieldset>
				</form></div>";

		return "$ret";
	}

	private function getCreateCategoryForm() {
		if(!isset($_POST[self::$create])) {
			return "<div class='aside-full'><div class='aside-split'>
						<form method='post' class='create-category-form'><input type='submit' name='".self::$create."' value='Create New Category'/></form>
					</div></div>";
		} else {
			return "<div class='aside-full'><div class='aside-split'><form method='post' class='create-category-form'>
						<fieldset>
							<legend><h5>Create new category</h5></legend>
							<span>
								<label for='".self::$category."'>Category:</label>
								<input type='text' id='".self::$category."' name='".self::$category."' value=''/>
							</span>
							<span>
								<label for='".self::$level."'>Level:</label>
								<select name='".self::$level."' id='".self::$level."'>
									<option value='1'>Primary</option>
									<option value='2'>Secondary</option>
								 	<option value='3'>Tertiary</option>
								</select>
							</span>
							<div class='update-confirm'>
								<input type='submit' name='".self::$cancel."' value='Cancel' class='cancel-button'/>
								<input type='submit' name='".self::$confirm."' value='Add Category' class='confirm-button'/>
							</div>
						</fieldset>
					</form></div></div>";
		}
	}

	private function getSEOStringURL($string) {
		$string = strtolower($string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		$string = preg_replace("/[\s-]+/", " ", $string);
		$string = preg_replace("/[\s_]/", "-", $string);

    	return $string;
	}

	private function getCheck($cid) {
		if(count($this->getProductCategoryArray()) > 0) {
			foreach ($this->getProductCategoryArray() as $c) {
				if($c->getCategoryID() == $cid) {
					return "checked";
				}
			}
		}
		return "";
	}

	public function updateCheckArrays() {
		assert($this->adminWantsToUpdateCategories());
		if(isset($_POST["".self::$check.""])) {
			$check = $_POST["".self::$check.""];

			foreach ($check as $id) {
				if($this->isChecked($id) == true) {
					$this->addCategoryArray[] = $id;
				}
			}

			if(count($this->getProductCategoryArray()) > 0) {
				foreach ($this->getProductCategoryArray() as $c) {
					if($this->isUnchecked($c->getCategoryID(), $check) == true) {
						$this->deleteCategoryArray[] = $c->getCategoryID();
					}
				}
			}
		}
		
		else if(count($this->getProductCategoryArray()) > 0) {
			foreach ($this->getProductCategoryArray() as $c) {
				$this->deleteCategoryArray[] = $c->getCategoryID();		
			}
			//$this->deleteCategoryArray[] = $this->getProductCategoryArray()[0]->getCategoryID();
		}
	}

	public function isChecked($id) {
		if(count($this->getProductCategoryArray()) > 0) {
			foreach ($this->getProductCategoryArray() as $c) {
				if($id == $c->getCategoryID()) {
					return false;
				}		
			}
		}
		return true;
	}

	public function isUnchecked($cid, $check) {

			foreach ($check as $id) {
				if($cid == $id) {
					return false;
				}
			}
		
		return true;
	}

	private function getCategoryArray() {
		try{
			return $this->model->getAllCategories();
		}
		catch(\PDOTableEmptyException $e) {
			$this->message = $e->getMessage();
		}
	}

	private function getProductCategoryArray() {
		try{
			return $this->model->getProductCategories($this->product->getProductID());
		}
		catch(\PDOTableEmptyException $e) {
			$this->message = $e->getMessage();
		}
	}

	public function getAddCategoryArray() {
		if(count($this->addCategoryArray) > 0) {
			return $this->addCategoryArray;
		}
		return null;
	}

	public function getDeleteCategoryArray() {
		if(count($this->deleteCategoryArray) > 0) {
			return $this->deleteCategoryArray;
		}
		return null;
	}

	private function getCategoryName() {
		if (isset($_POST[self::$category]))
			return trim($_POST[self::$category]);
	}

	private function getLevel() {
		if (isset($_POST[self::$level]))
			return trim($_POST[self::$level]);
	}
}