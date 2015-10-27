<?php 

namespace view;

class CreateProductView {
	
	private static $messageID = "CreateProductView::Message";
	private static $title = "CreateProductView::Title";
	private static $file = "CreateProductView::File";
	private static $desc = "CreateProductView::Desc";
	private static $price = "CreateProductView::Price";
	private static $unique = "CreateProductView::Unique";
	private static $create = "CreateProductView::Create";

	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	private $message;
	
	public function __construct() {

	}

	public function adminWantsToAddProduct() {
		return isset($_POST[self::$create]);
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

	public function getImage() {
		$this->message = "";

		try {
			return new \model\Image($this->getFile());
		}
		catch(\FileMissingException $e) {
			$this->message = $e->getMessage();
		}
		catch(\FileExistsException $e) {
			$this->message = $e->getMessage();
		}
		catch(\FileSizeTooLargeException $e) {
			$this->message = $e->getMessage();
		}
		catch(\FileWrongFormatException $e) {
			$this->message = $e->getMessage();
		}
		catch(\FilenameWrongLengthException $e) {
			$this->message = $e->getMessage();
		}
		catch(\FileNotImageException $e) {
			$this->message = $e->getMessage();
		}
		catch(\Exception $e) {
			$this->message = $e->getMessage();
		}
	}

	public function getProduct($filename) {
		$this->message = "";
			

		try {
			return new \model\Product($this->getTitle(), 
										$filename, 
										$this->getDesc(), 
										$this->getPrice(),
										$this->getSEOStringURL($this->getUnique()));		
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

	private function getHTML() {
		return "<form method='post' enctype='multipart/form-data'> 
				<fieldset>
					<legend>Add new product to database</legend>
					<p id='".self::$messageID."'>".$this->message."</p>

					<span>
					<label for='".self::$title."'>Title:</label>
					<input type='text' id='".self::$title."' name='".self::$title."' value='".$this->getTitle()."'/>
					</span>

    				<span>
					<label for='".self::$desc."'>Description:</label>
					<textarea id='".self::$desc."' name='".self::$desc."'>".$this->getDesc()."</textarea>
					</span>

					<span>
					<label for='".self::$price."'>Price:</label>
					<input type='text' id='".self::$price."' name='".self::$price."' value='".$this->getPrice()."'/>
					</span>

					<span>
					<label for='".self::$unique."'>Unique:</label>
					<input type='text' id='".self::$unique."' name='".self::$unique."' value='".$this->getUnique()."'/>
					</span>

					<span>
					<label for='".self::$file."'>File to upload:</label>
    				<div class='fileinputsbg'>
    					<div class='fileinputs'>
    						<input type='file' id='".self::$file."' name='".self::$file."'>
    					</div>
    				</div>
    				</span>

					<input type='submit' name='".self::$create."' value='Add product'/>
				</fieldset>
			</form>
		";
	}

	private function getSEOStringURL ($string) {
		$string = strtolower($string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		$string = preg_replace("/[\s-]+/", " ", $string);
		$string = preg_replace("/[\s_]/", "-", $string);

    	return $string;
	}

	private function getTitle() {
		if (isset($_POST[self::$title]))
			return trim($_POST[self::$title]);
	}

	public function getFile() {
		if (isset($_FILES[self::$file]))
			return $_FILES[self::$file];
		return "";
	}

	private function getDesc() {
		if (isset($_POST[self::$desc]))
			return trim($_POST[self::$desc]);
	}

	private function getPrice() {
		if (isset($_POST[self::$price]))
			return trim($_POST[self::$price]);
	}

	private function getUnique() {
		if (isset($_POST[self::$unique]))
			return trim($_POST[self::$unique]);
	}
}