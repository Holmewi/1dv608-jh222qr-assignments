<?php

namespace view;

class LogView {
	
	private $model;
	private $sortedLogArray = array();

	public function __construct(\model\Logger $model) {
		$this->model = $model;
	}

	private function sortArray() {
		$logArray = $this->model->getLogArray();
		$this->sortedLogArray = $this->sortObjectsInArrayByField($logArray, 'm_microTime', 'DESC');
	}

	/**
	*	@author Brainstorms of a Webdev
	*	SOURCE: http://www.bswebdev.com/2012/12/php-snippet-of-the-day-sort-object-by-field-name/
	*
	* 	@param array $objects Array of objects to sort.
 	* 	@param string $on Name of field.
 	* 	@param string $order (ASC|DESC)
 	*	@return array
	*/
	private function sortObjectsInArrayByField(&$array, $field, $order = 'ASC') {
		$comparer = ($order === 'DESC')
	        ? "return -strcmp(\$a->{$field},\$b->{$field});"
	        : "return strcmp(\$a->{$field},\$b->{$field});";
	    usort($array, create_function('$a,$b', $comparer));
	    return $array;
	}

	public function getHTML() {
		$this->sortArray();
		// CREATE LIST OF LOGS
		return 'logs';
	}
}