<?php

namespace model;

class ImageDAL {
	private static $path = \Settings::IMG_PATH;

	public function uploadImage(\model\Image $image) {
		move_uploaded_file($image->getTmpName(), self::$path . $image->getFilename());
	}	
}