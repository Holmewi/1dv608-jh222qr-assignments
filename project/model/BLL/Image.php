<?php

namespace model;

class Image {
	private static $path = \Settings::IMG_PATH;
	private $image;

	public function __construct($file) {

		if($file["size"] <= 0) {
			throw new \FileMissingException("File is missing, you need to add an image file.");
		}

		$filePath = self::$path . basename($file["name"]);

		$imageFileType = pathinfo($filePath, PATHINFO_EXTENSION);
		$isFileImage = getimagesize($file["tmp_name"]);
		$file["name"] = $this->getSEOStringURL($file["name"]);

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
			//move_uploaded_file($file["tmp_name"], $this->filePath . $newName);
		} else {
			throw new \FileNotImageException("The file is not an image.");
		}
		
		$this->image = $file;
	}

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