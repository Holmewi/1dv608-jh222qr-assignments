<?php

namespace model;

class LogRepository {

	private static $path = "./archive/log.xml";
	private static $txt = "./archive/log.txt";
	private static $rootElement = "archive";
	private $logArray = array();

	public function __construct() {

	}

	/**
	*	@return array of all the logs
	*/
	public function getLogArray() {
		return $this->logArray;
	}

	/**
	*	Save an object to a text file
	*	@param \model\LogItemBLL
	*/
	public function saveToFile(\model\LogItemBLL $item) {
		$xmlItem = serialize($item);

		$fp = fopen(self::$txt, "a");
		fwrite($fp, "[LOG]\n"); 
	    fwrite($fp, $xmlItem);
	    fwrite($fp, "\n[END]\n");
	    fclose($fp);
	}

	/**
	*	Load all objects from a text file to an array
	*/
	public function loadFromFile() {
		$lines = file(self::$txt);
		$start = "[LOG]";

		foreach ($lines as $value) {
			
			if($value === "[LOG]\n") {
				$line = "";
			} 
			else if($value === "[END]\n") {
				$item = unserialize($line);
				$this->logArray[] = $item;
			} else {
				$line .= $value;
			}
		}
	}

	/**
	*	THIS METHOD IS NOT USED!
	*	It was an attept to store a serialized object in a XML-file.
	*	The serialize string was cropped when inserted in the XML-file.
	*/
	public function loadLogFromXML() {
		$xmlparser = xml_parser_create();

		$fp = fopen(self::$path, 'r');
		$xmldata = fread($fp, 4096);

		xml_parse_into_struct($xmlparser,$xmldata,$values);
		xml_parser_free($xmlparser);

		foreach ($values as $key => $value) {
			
		}
	}

	/**
	*	THIS METHOD IS NOT USED!
	*	It was an attept to store a serialized object in a XML-file.
	*	The serialize string was cropped when inserted in the XML-file.
	*/
	public function saveLogToXML(\model\LogItemBLL $item) {	
		
		$this->xml = new \DOMDocument("1.0", "UTF-8");
		$xml->formatOutput = true;

		if(!file_exists(self::$path)) {		
			$root = $this->xml->createElement(self::$rootElement);
			$this->xml->appendChild($root);
			$this->xml->save(self::$path);
		} else {
			$this->xml->load(self::$path);
		}

		$xmlItem = serialize($item);
	
		$root = $this->xml->getElementsByTagName("archive")->item(0);
		$log = $this->xml->createElement("log", $xmlItem);
		$root->appendChild($log);

		$this->xml->save(self::$path);
	}	
}