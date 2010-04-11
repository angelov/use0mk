<?php

/**
 * use0mk.class.php v1.0
 *
 * 0.mk - Shorten your links!
 * http://0.mk
 * 
 * 0.mk API Documentation: http://0.mk/api
 * 
 * Newer versions of this class will be available on http://0.mk/use0mk
 */
 
class use0mk {

	var $user,
		$apiKey,
		$apiDomain = "http://api.0.mk/v2";

	function __construct($user = null, $apikey = null) {
	
		$this->user = $user;
		$this->apiKey = $apikey;
	
	}
	
	function shortenUrl($url = null) {
	
		if (isset($this->user) && isset($this->apiKey)) {
			$api = $this->apiDomain."/skrati?format=json&korisnik=".$this->user."&apikey=".$this->apiKey."&link=".$url;
		} else {
			$api = $this->apiDomain."/skrati?format=json&link=".$url;
		}
		
		$result = json_decode($this->getUrlContent($api));
		
		if ($result->status == 1) { // sucessful, return the short link 
		
			return $result->kratok;
		
		} else { // error, return the original link
		
			return $url;
		
		}	
	
	}
	
	function previewUrl($url = null) {
	
		$api = $this->apiDomain."/pregled?link=".$url;
		$result = json_decode($this->getUrlContent($api));
		
		if ($result->status == 1) {
			return $result->dolg;
		} else {
			return $url;
		}
	
	}
	
	function shortenAll($string = null) {

		$pattern = "/\b(http?|ftp|file|https):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i";
		$links = array();
		preg_match_all($pattern, $string, $links);
		
		for ($i=0; $i<=count($links[0]); $i++) {
		
			$short = $this->shortenUrl($links[0][$i]);  
			$string = str_replace($links[0][$i], $short, $string); 
		
		}
		
		return $string;
	
	}
	
	private function getUrlContent($url) {
    
        if (function_exists('curl_init')) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            $content = curl_exec($curl);
            curl_close($curl);

	        return $content;
                
        } else {
            return file_get_contents($url);
        }
        
    } 


}

?>