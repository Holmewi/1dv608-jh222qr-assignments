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
	        		<div id="wrapper">
		          		<h1>Project</h1>
		          			
		          
		          		<div id="container">
		          			' . $container . '
		          		</div>
		          		<aside>
		          			' . $aside . '
		          		</aside>
	          		</div>
	         	</body>
	      	</html>
    	';
  	}
}