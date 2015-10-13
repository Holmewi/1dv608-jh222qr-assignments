<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */
/**
 * The settings file contains installation specific information
 * 
 */
class Settings {


	/**
	 * The app session name allows different apps on the same webhotel to share a virtual session
	 */
	const APP_SESSION_NAME = "LoginLab";
	
	/**
	 * Username of default user
	 */
	const USERNAME = "Admin";

	/**
	 * Password of default user
	 */
	const PASSWORD = "Password";

	/**
	 * Path to folder writable by www-data but not accessable by webserver
	 */
	const DATAPATH = "../data/";

	/**
	 * Salt for creating temporary passwords
	 * Should be a random string like "feje3-#GS"
	 */
	const SALT = "feje3-#GS";

	/**
	 * Show errors 
	 * boolean true | false
	 */
	const DISPLAY_ERRORS = true;

	/**
	 * Database name
	 */
	const SERVER = 'localhost';

	/**
	 * Database name
	 */
	const DATABASE = 'education';

	/**
	 * Database username
	 */
	const DB_USERNAME = 'root';

	/**
	 * Database username
	 */
	const DB_PASSWORD = 'root';

	/**
	 * Database connection string
	 */
	const DB_CONNECTION_STRING = 'connectionstring';

	
}