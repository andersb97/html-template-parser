<?php
/*
	Author: Anders Hansen
	Email: anders@muy.dk
	Last edit: 09-12-18 18.25
	
	File documentation:
	This simple parser will replace placeholders on HTML templates, works on raw-html, and from URLs.
	
	* Example use:
	First, define all placeholders in the HTML document, e.g. %placeholder_name%. Each placeholder name must be unique.
	Then define the placeholder names, and value in the object below (without the %)
	
	* Function parameters and values:
	(1): Can either be the URL to load HTML from, or raw HTML string.
	(2): The second parameter (type), is what determines the input type. 0 = URL, 1 = HTML string
	(3): Required, object of the placeholders.
	
	* Example to call the function:
	$result = templateParser("https://example.com/template.html", 0, $placeholders);
	
*/

	// Define placeholders.
	$placeholders = [
		"page_title" => "Test",
		"page_meta_description" => "Dette er meta beskrivelsen...",
		"page_paragraph" => "Yep... det virker.",
	];
	
	// Let's run it.
	echo templateParser('<!doctype html><html lang="en"><head> <meta charset="utf-8"> <title>%page_title%</title> <meta name="description" content="%page_meta_description%"> <meta name="author" content="Anders"></head><body><p>%page_paragraph%</p></body></html>', 1, $placeholders);
	
	
	
	// Function to load HTML template, and return the output.
	function templateParser($data,$type,$object){
		if($type == 0){
			
			// URL
			// Check if server has allow_url_fopen enabled.
			if(ini_get('allow_url_fopen')){
				
				if(filter_var($data, FILTER_VALIDATE_URL)){
					$html = file_get_contents($data);
					if($html === FALSE){
						exit("There was an error parsing the URL: ".htmlspecialchars($data));
					}else{
						// Data was received, replace the placeholders.
						foreach ($object as $key => $value) {
							// Attempt to replace the placeholder value.
							$html = str_replace('%'.$key.'%',$value,$html);
						}
						return $html;
					}
				}else{
					exit("Invalid URL.");
				}
				
			}else{
				exit("The server does not have allow_url_fopen enabled.");
			}
			
		}elseif($type == 1){
			
			// Raw HTML string
			foreach ($object as $key => $value) {
				// Attempt to replace the placeholder value.
				$data = str_replace('%'.$key.'%',$value,$data);
			}
			return $data;
			
		}else{
			exit("Invalid type value.");
		}
		
	}

?>