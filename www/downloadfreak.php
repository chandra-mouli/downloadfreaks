<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
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

function getTags( $dom, $tagName, $attrName, $attrValue ){
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
function getLinksFromSite($link_url,$isGoogle){
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
	curl_close($ch); 
	
	
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
?> 



<?php

if ($_POST != NULL)
{
$links=getLinksFromSite("http://www.google.com/search?q=intitle:index.of+".$_POST['sample']." pdf epub",true);
$finalLinks=array();
foreach ($links as $link){
	$siteLinks=getLinksFromSite($link,false);
	foreach($siteLinks as $sLink){
		if(substr($sLink, -4) === ".mp3" || substr($sLink, -4) === ".pdf" || substr($sLink, -5) === ".epub"){
		// || strncmp($sLink, ".mobi",-5) == 0 || strncmp($sLink, ".pdf",-4) == 0 || strncmp($sLink, ".djvu",-5) == 0 || strncmp($sLink, ".ps",-3) == 0 || strncmp($sLink, ".pdb",-4) == 0 || strncmp($sLink, ".ibooks",-7) == 0 || strncmp($sLink, ".lit",-4) == 0 || strncmp($sLink, ".prc",-4) == 0 || strncmp($sLink, ".opf",-4) == 0 ){
		print "<a href=\"".$sLink."\">".$sLink."</a><br/>";
		ob_flush();
		flush();
	}
	array_push($finalLinks,$sLink);
	}
}
/*
foreach ($finalLinks as $link){
	print $link."<br/>";
}
*/
}
?>

<form action ="downloadfreak.php" method=post> 
Search: <input name="sample" type="text" />
<input type="submit" /> 
</form>
