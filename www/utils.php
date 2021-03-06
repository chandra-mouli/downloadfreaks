<?php
	function clean($link){
		$laspos = strrpos($link,"/");
		$link = substr($link,$laspos+1);
		$link = str_replace(".html","",$link);
		$link = str_replace(".mp3","",$link);
		$link = str_replace("-"," ",$link);
		return ucwords($link);
	}


	function getHTML($link) 
	{ 
		
		$ch = curl_init();
		$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($ch, CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXY, "netmon.iitb.ac.in");
		curl_setopt($ch, CURLOPT_PROXYPORT, 80);
		curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "sravanb:neverignore!");	
		$html = curl_exec($ch);
		
				
		if (!$html) {
			 $html = "<br />cURL error number:" .curl_errno($ch);
			 $html.= "<br />cURL error:" . curl_error($ch);
		}
			curl_close($ch); 

		return $html;

	} 
		
	function DOMinnerHTML($element) 
	{ 
		$innerHTML = ""; 
		$children = $element->childNodes; 
		foreach ($children as $child) 
		{ 
		    $tmp_dom = new DOMDocument(); 
		    $tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
		    $innerHTML.=trim($tmp_dom->saveHTML()); 
		} 
		return $innerHTML; 
	} 

	function endsWith($haystack,$needle,$case=true) {
		if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
		return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
	}
		
	function getTags( $dom, $tagName, $attrName, $attrValue ){
		$html = '';
		$domxpath = new DOMXPath($dom);
		$newDom = new DOMDocument;
		$newDom->formatOutput = true;

		$filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");

		$i = 0;
		while( $myItem = $filtered->item($i++) ){
		    $node = $newDom->importNode( $myItem, true );    // import node
		    $newDom->appendChild($node);                    // append node
		}
		$html = $newDom->saveHTML();
		return $html;
	}

	function getLinksFromSite($link_url,$isGoogle){
	
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		require_once 'url/URLResolver.php';
		$resolver = new URLResolver();
		$link_url=($resolver->resolveURL($link_url)->getURL());
	
		$html_dump = getHTML($link_url);
		
		
		/**
		*Dom code
		**/
	
		$dom = new DOMDocument();
		@$dom->loadHTML($html_dump);
		
		if($isGoogle){
			$search_results = getTags($dom, "li","class","g");
			$dom2 = new DOMDocument();
			@$dom2->loadHTML($search_results);
			$dom=$dom2;
			print "cool<br>";
		}
		$hrefs = $dom->getElementsByTagName("a");
		$links =array();
		echo $hrefs->length;
		echo "<br>";
		$count=0;
		foreach ($hrefs as $url)
		{
			if(true){
				if(strncmp($url->getAttribute("href"), "/url",4) === 0 && $count<3){
					$li=($resolver->resolveURL("http://google.co.in".$url->getAttribute("href"))->getURL());
					array_push($links,$li);
					$count+=1;
				}
			}
			else{
				array_push($links,$url->getAttribute("href"));
			}
			
		}
		return $links;
	}
	
	function getPDFs($link_url,$limit){
	
		error_reporting(E_ERROR );
		//require_once 'url/URLResolver.php';
		//$resolver = new URLResolver();
		//$link_url=($resolver->resolveURL($link_url)->getURL());
		$html_dump = getHTML($link_url);
		//echo $html_dump;
		$dom = new DOMDocument();
		@$dom->loadHTML($html_dump);
		//echo $html_dump;
		$search_results = getTags($dom, "div","class","button_download");
		$dom2 = new DOMDocument();
		@$dom2->loadHTML($search_results);
		$dom=$dom2;

		$hrefs = $dom->getElementsByTagName("a");
		$links ="";
		$count=0;
		foreach ($hrefs as $url)
		{
			if($count>=$limit) break;
			$name = str_replace("Download","",$url->getAttribute('title'));
			$href = $url->getAttribute('href');
			
			$ht = "<a href=\"".$href."\">".clean($name)."</a><br>";
			$links.=$ht;
			
			$count+=1;
		}
		return $links;
	}
	
	function multiRequest($data, $options = array()) {
	  // array of curl handles
	  $curly = array();
	  // data to be returned
	  $result = "";

	  // multi handle
	  $mh = curl_multi_init();

	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($data as $id => $d) {

		$curly[$id] = curl_init();

		$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
		
		$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
		curl_setopt($curly[$id], CURLOPT_USERAGENT, $userAgent);
		curl_setopt($curly[$id], CURLOPT_PROXY, "netmon.iitb.ac.in");
		curl_setopt($curly[$id], CURLOPT_PROXYPORT, 80);
		curl_setopt ($curly[$id], CURLOPT_PROXYUSERPWD, "sravanb:neverignore!");	
		
		
		curl_setopt($curly[$id], CURLOPT_URL,            $url);
		curl_setopt($curly[$id], CURLOPT_HEADER,         0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);


		
		curl_multi_add_handle($mh, $curly[$id]);
	  }

	  // execute the handles
	  $running = null;
	  do {
		curl_multi_exec($mh, $running);
	  } while($running > 0);

	  // get content and remove handles
	  foreach($curly as $id => $c) {
		$x = curl_multi_getcontent($c);
		$result.=mp3($x);
		curl_multi_remove_handle($mh, $c);
	  }

	  // all done
	  curl_multi_close($mh);

	  return $result;
	}
	
	

function getTags1( $dom, $tagName, $attrName, $attrValue ){
    $html = '';
    $domxpath = new DOMXPath($dom);
    $newDom = new DOMDocument;
    $newDom->formatOutput = true;

    $filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");
    // $filtered =  $domxpath->query('//div[@class="className"]');
    // '//' when you don't know 'absolute' path

    // since above returns DomNodeList Object
    // I use following routine to convert it to string(html); copied it from someone's post in this site. Thank you.
    $i = 0;
    while( $myItem = $filtered->item($i++) ){
        $node = $newDom->importNode( $myItem, true );    // import node
        $newDom->appendChild($node);                    // append node
    }
    $html = $newDom->saveHTML();
    return $html;
}
function getLinksFromSite1($link_url,$isGoogle){
	require_once 'url/URLResolver.php';
	$resolver = new URLResolver();
	
	$link_url=($resolver->resolveURL($link_url)->getURL());
	
	/**
	*curl code
	**/
	$ch = curl_init();
	$userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt ($ch, CURLOPT_URL,$link_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PROXY, "netmon.iitb.ac.in");
	curl_setopt($ch, CURLOPT_PROXYPORT, 80);
	curl_setopt ($ch, CURLOPT_PROXYUSERPWD, "sravanb:neverignore!");
	$html_dump = curl_exec($ch);
	echo $html_dump;
	curl_close($ch); 
	
	
	/**
	*Dom code
	**/
	
	$dom = new DOMDocument();
    @$dom->loadHTML($html_dump);
	if($isGoogle){
	$search_results = getTags1($dom, "div","class","g");
	$dom2 = new DOMDocument();
	@$dom2->loadHTML($search_results);
	$dom=$dom2;
	}
	$hrefs = $dom->getElementsByTagName("a");
	$links =array();
	foreach ($hrefs as $url)
	{
	if(substr($url->getAttribute("href"),4) !== "http"){
		if($isGoogle){
		array_push($links,"www.google.com".$url->getAttribute("href"));
	}
	else {
		array_push($links,$link_url.$url->getAttribute("href"));
	}
		}
	else{
		array_push($links,$url->getAttribute("href"));
	
	}
	}
	return $links;
} 
	
	function getLinks($query){
		
		
		$url="http://www.google.co.in/cse?start=0&num=3&q=intitle:index.of+".urlencode($query)." mp3&client=google-csbe";
//echo $url;
$links=getLinksFromSite1($url,true);
//echo $links[0];

foreach ($links as $link){
	echo "\n\n\n\n\n";
	$siteLinks=getLinksFromSite1($link,false);
	foreach($siteLinks as $sLink){
		echo $sLink;
		if($sLink->length<5)continue;
		if(substr($sLink, -4) === ".mp4" || substr($sLink, -4) === ".mp3" || substr($sLink, -4) === ".pdf" || substr($sLink, -5) === ".epub"){
		// || strncmp($sLink, ".mobi",-5) == 0 || strncmp($sLink, ".pdf",-4) == 0 || strncmp($sLink, ".djvu",-5) == 0 || strncmp($sLink, ".ps",-3) == 0 || strncmp($sLink, ".pdb",-4) == 0 || strncmp($sLink, ".ibooks",-7) == 0 || strncmp($sLink, ".lit",-4) == 0 || strncmp($sLink, ".prc",-4) == 0 || strncmp($sLink, ".opf",-4) == 0 ){
		print "<a href=\"".$sLink."\">".clean(urldecode($sLink))."</a><br/>";
		ob_flush();
		flush();
	}
	//array_push($finalLinks,$sLink);
	}
}
}
	
	
	
	
?>
