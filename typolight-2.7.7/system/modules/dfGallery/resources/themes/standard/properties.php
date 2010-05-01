<?php
$properties = array ( 
);
$properties [0] = array (
		'name' => 'Music files', 
		'description' => 'Enter each absolute url of the mp3 file in a seperate line.<br>or you could leave it blank if you want music disabled.', 
		'properties' => array ( 
		) 
);
$properties [0] ['properties'] [] = array (
		'display_name' => 'music files', 
		'name' => 'music', 
		'default_value' => '', 
		'type' => 'textarea', 
		'rule' => 'min_length[0]' 
);

$properties [1] = array (
		'name' => 'Available theme properties', 
		'description' => 'Please do not edit theme properties if you are not sure what they do.', 
		'properties' => array ( 
		) 
);

$properties [1] ['properties'] [] = array (
		'display_name' => 'pause slideshow at start', 
		'name' => 'slideshow_pauseAtStart', 
		'rule' => 'required', 
		'default_value' => 'true', 
		'type' => 'radio', 
		'values' => array (
				'true', 
				'false' 
		) 
);

$properties [1] ['properties'] [] = array (
		'display_name' => 'slideshow interval', 
		'name' => 'slideshow_interval', 
		'rule' => 'required', 
		'default_value' => 5 
);

$properties [1] ['properties'] [] = array (
		'display_name' => 'Load skin configurations', 
		'name' => 'use_skin_config', 
		'default_value' => 'true', 
		'type' => 'radio', 
		'values' => array (
				'true' 
		), 
		'readonly' => 'true' 
);

?>