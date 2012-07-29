<?php
	require_once("utils.php");
	
	
	function mp3($html){
		//$html = getHTML($inplink);
		//get the dom data into $dom
		
		
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		
		$hrefs = $dom->getElementsByTagName("a");
		
		foreach ($hrefs as $anchor){
			$link = $anchor->getAttribute('href');	
			if(endsWith($link,'.mp3')){
				$ht = "<a href=\"".$link."\">".clean($link)."</a><br>";
				echo $ht;
				flush();
				ob_flush();
				return $ht;
			}
		    else if (  strncmp($link, "http://www.megaupload.com",25) === 0 
				    || strncmp($link, "http://www.rapidshare.com",25) === 0) 
		    
		    {
				$ht = "<a style=\"color:red\" href=\"".$link."\">".$link."</a><br>";
				echo $ht;
				flush();
			ob_flush();
				return $ht;
			}
		}
		return "";
		
	}
	function doregama($link){
		$html = getHTML($link);
	
		//get the dom data into $dom
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		
		$hrefs = $dom->getElementsByTagName("a");
		$final = "";
		$tmpList = array();
		foreach ($hrefs as $anchor ){
			$innertext  = DOMinnerHTML($anchor);
			$link = $anchor->getAttribute('href');	
			if (strpos($innertext,'Download Link') !== false  || strpos($link,'rockdaway.com') !== false) {
				array_push($tmpList,$link);
				//$final  .= mp3($link);
			}
		}
		
		$htmls = multiRequest($tmpList);
		if($htmls == "") echo "No Results Found<br> \n"; 
		
		
		return $final;
	}
	function books($query,$limit=10){
		$query = str_replace(" ", "-", trim($query));
		$link = "http://www.pdfs-free.com/".$query."-pdf.html";
		
		//$html =  getHTML($link);

		$x= clean(str_replace("-pdf","",$link))."<br>".getPDFs($link,$limit);
		print $x;
		return $x;
		
	}	
	
	/*
	if($_GET!== NULL){
		$query = $_GET['q'];
	}
	*/
	
	function songs($query){
		$query.=" site:doregama.in";
		$query = "https://www.google.co.in/search?q=".urlencode($query);
		$googleRes = getLinksFromSite($query,false);
		$ret = "";
		foreach($googleRes as $lk){
			echo "<br>".clean($lk)."<br>";
			flush();
			ob_flush();
			$dore = doregama($lk);
			
			$ret.="<br><br>".$lk."<br>".$dore;
		}
		
		return $ret;
	}
	require("downloadfreak.php");
	function main($query){
		
		$query = trim($query);
		if($query[0]=='!' && $query[1]!='b') songs(substr($query,2));
		elseif($query[0]=='!') books(substr($query,10));
		else {
			echo "getting links\n <br>";
			getLinks($query);
		}
	}
	
	//songs("eka tha tiger");
	//books("pride and prejudice",10);
/*
	if($_GET!== NULL){
		$query = $_GET['query'];
		main($query);
	}
*/	
	
	
?>
