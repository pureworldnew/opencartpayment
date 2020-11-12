<?php

// ***************************************************
//                  Search Analytics    
//  
//       Standalone extension and component of 
//               Advanced Smart Search
//
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************

class Search_analytics {
	
	public function __construct() {
	}

	// Replaces multiple spaces with a single space and 
	// removes extra spaces from the beginning and end of keyphrases
	public function sanitize_keyphrases($keyphrase) {

		// Replace multiple spaces/tabs with a single space
		$keyphrase = preg_replace('/\s+/', ' ', $keyphrase);
	
		// Strip white spaces, tabs and other blank characters from the beginning and end of the string
		$keyphrase = trim($keyphrase);

		if ( !empty($keyphrase) ) {
	
			return $keyphrase;
		} else {
	
			return false;
		}
	}
} //class end 
