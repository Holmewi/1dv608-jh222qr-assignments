<?php

namespace model;

class Image {

	/**
	 *	Settings for path of images
	 *	@var string
	 */
	private static $path = \Settings::LARGE_IMG_PATH;

	private $image;

	public function __construct($file) {

		if($file["size"] <= 0) {
			throw new \FileMissingException("File is missing, you need to add an image file.");
		}
		// Create folder if it does not exist
		if (!file_exists(self::$path)) {
		    mkdir(self::$path, 0777, true);
		}

		// Makes the filename SEO friendly
		$file["name"] = $this->getSEOStringURL($file["name"]);

		$filePath = self::$path . basename($file["name"]);
		$imageFileType = pathinfo($filePath, PATHINFO_EXTENSION);
		$isFileImage = getimagesize($file["tmp_name"]);
		
		if($isFileImage) {
			if(file_exists($filePath)) {
				throw new \FileExistsException("Filename already exists.");
			}
			if($file["size"] > 250000) {
				throw new \FileSizeTooLargeException("The file size is larger than 250K.");
			}
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
				throw new \FileWrongFormatException("The image file is not jpg, jpeg or png.");
			}
			if(strlen($file["name"]) <= 0 || strlen($file["name"]) > 64) {
				throw new \FilenameWrongLengthException("Filename must be between 1 and 64 characters.");
			}
		} else {
			throw new \FileNotImageException("The file is not an image.");
		}

		$this->image = $file;
	}

	/**
	 * 	Method to make a filename with extension SEO friendly
	 *	@param string $filename
	 * 	@return string
	 */
	private function getSEOStringURL ($filename) {
		$filename = strtolower($filename);
		$extension = "";

		$parts = explode(".", $filename);
		$filename = "";

		for($i = 0; $i < count($parts); $i++) {
			if($i == count($parts) - 1) {
				$extension = $parts[$i];
			} else {
				$filename .= $parts[$i];
			}	
		}
		
		$filename = preg_replace("/[^a-z0-9_\s-]/", "", $filename);
		$filename = preg_replace("/[\s-]+/", " ", $filename);
		$filename = preg_replace("/[\s_]/", "-", $filename);

		if(strlen($extension) > 0) {
			$filename = $filename . "." . $extension;
		}

    	return $filename;
	}
	
	public function uploadImage() {
		move_uploaded_file($this->image["tmp_name"], self::$path . $this->image["name"]);
		chmod(self::$path . $this->image["name"], 0666);
	}

	/**
	 * 	Method to make scale and crop image to different sizes
	 *	Source: http://code.tutsplus.com/articles/how-to-dynamically-create-thumbnails--net-1818
	 *	@param int $squareSideSize
	 *	@param string $newPath
	 */
	public function createSquareImage($squareSideSize, $newPath) {
		$filename = $this->getFilename();

		if(preg_match('/[.](jpg)$/', $filename)) {
        $img = imagecreatefromjpeg(self::$path . $filename);
	    } else if (preg_match('/[.](jpeg)$/', $filename)) {
	        $img = imagecreatefromgif(self::$path . $filename);
	    } else  if (preg_match('/[.](png)$/', $filename)) {
	        $img = imagecreatefrompng(self::$path . $filename);
	    }

	    // Capturing the original width and height of the image
	    $ox = imagesx($img);
	    $oy = imagesy($img);
	    
	    // Setting the new width and height of the image to aspect ratio 1:1
	    if($ox > $oy) {
	    	$nx = ($ox - $oy) / 2;
	    	$ny = 0;
	    	$smallest = $oy;
	    } else {
	    	$nx = 0;
	    	$ny = ($oy - $ox) / 2;
	    	$smallest = $ox;
	    }

	    $thumb = imagecreatetruecolor($squareSideSize, $squareSideSize);
	    imagecopyresampled($thumb, $img, 0, 0, $nx, $ny, $squareSideSize, $squareSideSize, $smallest, $smallest);

    	if (!file_exists($newPath)) {
		    mkdir($newPath, 0777, true);
		}

		imagepng($thumb, $newPath . $filename);
		chmod($newPath . $filename, 0666);
	}

	public function getFilename() {
		return $this->image["name"];
	}

	public function getTmpName() {
		return $this->image["tmp_name"];
	}

	public function getImage() {
		return $this->image;
	}	
}