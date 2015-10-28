<?php

class Settings {

	/**
	 * Name of message session used to catch message outside view
	 */
	const MESSAGE_SESSION_NAME = "MessageSession";

	/**
	 * Path to original image server storage
	 */
	const LARGE_IMG_PATH = "files/large/";

	/**
	 * Path to thumnail image server storage
	 */
	const THUMB_IMG_PATH = "files/thumbs/";

	/**
	 * 
	 */
	const MEDIUM_IMG_PATH = "files/medium/";

	/**
	 * Final side size of cropped thumbnails image
	 */
	const THUMB_IMG_SIDE = 200;

	/**
	 * Final side size of cropped medium image
	 */
	const MEDIUM_IMG_SIDE = 400;
	
	/**
	 * Show errors: Booleaen true | false
	 */
	const DISPLAY_ERRORS = true;

	/**
	 * Database server host
	 */
	const SERVER = 'localhost';

	/**
	 * Database name
	 */
	const DATABASE = 'education';

	/**
	 * Auth database: username
	 */
	const DB_USERNAME = 'root';

	/**
	 * Auth database: password
	 */
	const DB_PASSWORD = 'root';
}