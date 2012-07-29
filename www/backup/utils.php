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
	
	
	
	
	
	
	
	
	
	
?>
