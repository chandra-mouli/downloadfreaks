<?php
	require_once("utils.php");
	
	
	function mp3($inplink){
	
		$html = getHTML($inplink);
		//get the dom data into $dom
		
		
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		
		$hrefs = $dom->getElementsByTagName("a");
		
		foreach ($hrefs as $anchor){
			$link = $anchor->getAttribute('href');	
			if(endsWith($link,'.mp3') || strncmp($link, "http://www.megaupload.com",25) === 0 || strncmp($link, "http://www.rapidshare.com",25) === 0) return $link	;
		}
		return "";
		
	}
	
	
	
	function doregama($link){
		$html = getHTML($link);
	
		//get the dom data into $dom
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		
		$hrefs = $dom->getElementsByTagName("a");
		$final = array();
		
		foreach ($hrefs as $anchor ){
			$innertext  = DOMinnerHTML($anchor);
			$link = $anchor->getAttribute('href');	
			if (strpos($innertext,'Download Link') !== false  || strpos($link,'rockdaway.com') !== false) {
				$mp3link  = mp3($link);
				if($mp3link!=="") array_push($final,$mp3link);	
				//echo $innertext." ".$link;
			}
		}
		return $final;
	}
	
	$query = "ek tha tiger";
	if($_GET!== NULL){
		$query = $_GET['q'];
	}
	
	
	/*
	$query.=" site:doregama.in";
	$query = "https://www.google.co.in/search?q=".urlencode($query);
	echo $query;
	$googleRes = getLinksFromSite($query,false);
	foreach($googleRes as $tmp){
		echo $tmp."<br/>";
		ob_flush();
		flush();
		
		$ls =  doregama($tmp);
		foreach($ls as $tmp){
			echo $tmp."<br>";
			ob_flush();
			flush();
		}
		echo "<br>";
		
	}
	
	*/
	function books($query,$limit){
		
		$query = str_replace(" ", "-", trim($query));
		
		
		$link = "http://www.pdfs-free.com/".$query."-pdf.html";
		echo $link;
		$html =  getHTML($link);
		echo $html;
		getPDFs($link,$limit);
		
		
		
		
	}
	books("harry potter",3);
?>
