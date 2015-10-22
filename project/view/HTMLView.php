<?php

namespace view;

class HTMLView {

	public function render(\view\CreateProductView $cpv) {
    	echo '<!DOCTYPE html>
	      	<html lang="en-GB">
	        	<head>
	          		<meta charset="utf-8">
	          		<link rel="stylesheet" type="text/css" href="css/style.css">
	          		<title>Project</title>
	        	</head>
	        	<body>
	          		<h1>Project</h1>
	          			
	          
	          		<div class="container">

	          		</div>
	          		<aside>
	          			' . $cpv->getResponse() . '
	          		</aside>
	         	</body>
	      	</html>
    	';
  	}
}