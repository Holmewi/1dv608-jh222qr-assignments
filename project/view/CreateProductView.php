<?php 

namespace view;

class CreateProductView {
	
	/**
	 *	These names are used in $_POST
	 *	@var string
	 */
	private static $messageID = "CreateProductView::Message";
	private static $title = "CreateProductView::Title";
	private static $file = "CreateProductView::File";
	private static $desc = "CreateProductView::Desc";
	private static $price = "CreateProductView::Price";
	private static $unique = "CreateProductView::Unique";
	private static $create = "CreateProductView::Create";

	/**
	 * 	This name is used in session
	 * 	@var string
	 */
	private static $sessionMessage = \Settings::MESSAGE_SESSION_NAME;

	private $message;
	
	public function __construct() {

	}

	/**
	 * Method to check if admin wants to view product
	 * @return boolean true if admin tried to view product
	 */
	public function adminWantsToAddProduct() {
		return isset($_POST[self::$create]);
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
	 * 	Method to get new image
	 * 	@return new \model\Image()
	 */
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

	/**
	 * 	Method to get new product
	 *	@param string $filename
	 * 	@return new \model\Product()
	 */
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

	/**
	 * 	Method to generate HTML
	 * 	@return string HTML
	 */
	private function getHTML() {
		return "<div class='aside-full'><div class='aside-split'><form method='post' enctype='multipart/form-data'> 
					<fieldset>
						<legend><h5>Add new product to database</h5></legend>
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

						<input class='submit-button' type='submit' name='".self::$create."' value='Add product'/>
					</fieldset>
				</form></div></div>";
	}

	/**
	 * 	Method to make a string SEO friendly
	 *	@param string $string
	 * 	@return string
	 */
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