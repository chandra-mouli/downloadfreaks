<html><head>  <meta name="author" content="Sai Teja Pratap">
    <title>Sai Teja Pratap</title>
    <link rel="stylesheet" href="stp.css" type="text/css">
    <!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="main.css" />
	<link rel="stylesheet" type="text/css" href="form.css" />

    
    
    <link href="http://www.cse.iitb.ac.in/%7Eteja/images/favi.png" rel="icon" type="image/x-icon">
</head>

<body>
<div id="main" style="" >
    <div id="top_pane" >
		<div id="logo" style="width:40%;float:left;padding-top:15;padding-left:60">Download Freaks</div>
		<div style="float:left;">
		<ul class="nav">
			<li class="nav pres"><a class="nava" href="">Home</a></li>
			<li class="nav"><a href="#" class="nava">About</a></li>
			<li class="nav"><a href="#" class="nava">Blog</a></li>       
			<!--li class="nav"><a href="gallery.html" class="nava">Gallery</a></li-->
			<li class="nav"><a href="#" class="nava">Contact</a></li>    </ul>
		</div>
		
    
	</div>
	
	
	<div id="col2" style="padding-top:20px;">
		<div style="text-align:center">
		<form action="stp.php">
			<input name="query" type="textfield" placeholder="!b pride and prejudice"> <input type="submit" value="search">
		</form>
		</div>
		<div style="padding-left:50">
			<?php
				error_reporting(E_ERROR);
				require("doregama.php");
				if($_GET!== NULL){
			$query = $_GET['query'];
			main($query);
								}
?>
<!--			  <br />
			  <b>Ishq 2012</b><br />
			  <a class="df" href="http://m.lisanym.com/telugu/Ishq%20(2012)/01%20-%20Lachhamma.mp3">01
			  Lachhamma</a><br />
			  <a class="df" href="http://m.lisanym.com/telugu/Ishq%20(2012)/02%20-%20Oh%20Priya%20Priya.mp3">02
			  Oh Priya Priya</a><br />
			  <a class="df" href="http://m.lisanym.com/telugu/Ishq%20(2012)/03%20-%20Sutiga%20Choodaku.mp3">03
			  Sutiga Choodaku</a><br />
			  <a class="df" href="http://m.lisanym.com/telugu/Ishq%20(2012)/05%20-%20Edho%20Edho.mp3">05 Edho
			  Edho</a><br />
			  <br />
			  <b>Aashiqui In 2011</b><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=K8CUN4QM">http://www.megaupload.com/?d=K8CUN4QM</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=DGXA7WKG">http://www.megaupload.com/?d=DGXA7WKG</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=IQE0283U">http://www.megaupload.com/?d=IQE0283U</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=NIE8A7MC">http://www.megaupload.com/?d=NIE8A7MC</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=JLCUMNQM">http://www.megaupload.com/?d=JLCUMNQM</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=800PRAIF">http://www.megaupload.com/?d=800PRAIF</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=9SXV83Q0">http://www.megaupload.com/?d=9SXV83Q0</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=ZJIVQ4JH">http://www.megaupload.com/?d=ZJIVQ4JH</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=QCTZA27Z">http://www.megaupload.com/?d=QCTZA27Z</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=4IG09OXZ">http://www.megaupload.com/?d=4IG09OXZ</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=79S388EU">http://www.megaupload.com/?d=79S388EU</a><br />
			  <a style="color:red" class="df"  href=
			  "http://www.megaupload.com/?d=DW885P50">http://www.megaupload.com/?d=DW885P50</a><br />
			  <br />
	-->	</div>
		
		
    
	</div>
	
</div>




</body></html>
