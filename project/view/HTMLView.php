<?php

namespace view;

class HTMLView {

	public function getHTML($container, $aside) {
    	echo '<!DOCTYPE html>
	      	<html>
	        	<head>
	          		<meta charset="utf-8">
	          		<link rel="stylesheet" type="text/css" href="css/style.css">
	          		<title>Project</title>
	        	</head>
	        	<body>
	          		<h1>Project</h1>
	          			
	          
	          		<div id="container">
	          			' . $container . '
	          		</div>
	          		<aside>
	          			' . $aside . '
	          		</aside>
	         	</body>
	      	</html>
    	';
  	}

	/*
	public function render(\view\CreateProductView $cpv, \view\ProductListView $plv, \view\NavigationView $nv) {
    	echo '<!DOCTYPE html>
	      	<html>
	        	<head>
	          		<meta charset="utf-8">
	          		<link rel="stylesheet" type="text/css" href="css/style.css">
	          		<title>Project</title>
	        	</head>
	        	<body>
	          		<h1>Project</h1>
	          			
	          
	          		<div id="container">
	          			' . $this->getContainerResponse($plv, $nv) . '
	          		</div>
	          		<aside>
	          			' . $cpv->getResponse() . '
	          		</aside>
	         	</body>
	      	</html>
    	';
  	}
	
  	private function getContainerResponse(\view\ProductListView $plv, \view\NavigationView $nv) {
  		if($nv->inProductView()) {
  			$pv = $plv->getProductView();
  			return $pv->getHTML();
  		}
  		return $plv->getHTML();
  	}
  	*/
}