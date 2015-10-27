<?php

namespace view;

class HandleCategoryView {
	
	public function getResponse() {
		//if(empty($this->message)) {
		//	$this->message = $this->getSessionMessage();
		//}
		return $this->getHTML();
	}

	private function getHTML() {
		return "Edit product categories";
	}
}