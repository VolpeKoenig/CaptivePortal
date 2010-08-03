<?php

if (!isset($_GET['add'])) {
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; 
charset=utf-8" />
		<meta name="viewport" content="width=device-width, 
user-scalable=no" />
		<title>CaptivePortal</title>
		<script type="text/javascript" charset="utf-8">
			window.addEventListener("load", 
function(){window.scrollTo(0,1)});
		</script>
		<style type="text/css" media="screen">
		
			body {
				margin: 0;
				padding: 0;
/*  			width: 320px; */
				font-family: Helvetica, sans-serif;
				font-size: 14px;
				-webkit-text-size-adjust: none;
				background-color: white;
				background: url(background.png) repeat;
			}

			#wrapper {
				margin: 0;
				padding: 0px 12px 15px 12px;
				text-align: center;
				border: 1px solid #c5ccd3;
			}

			h1 {
				font-size: 23px;
				text-shadow: #fff 0px 1px 1px;
				color: #4c566c;
				margin: 15px 10px 8px 20px;
				padding: 0;
			}
			
			h2 {
				font-size: 16px;
				text-shadow: #fff 0px 1px 1px;
				color: #4c566c;
				margin: 15px 10px 8px 20px;
				padding: 0;
			}

			h3 {
				font-size: 14px;
				margin-left: 10px;
				padding: 0;
				text-align: left;
			}
			
			div.content {
				margin: 10px;
				padding: 0;
				width: 100%;
				max-width: 640px;
				background-color: white;
				-webkit-border-radius: 8px;
				-moz-border-radius: 8px;
				border: 1px solid #aaa;
				margin-left: auto ;
				margin-right: auto ;
			}
			
			div.content p {
				margin: 0;
				font-weight: normal;
				padding: 10px;
				text-align: left;
				text-indent: 20px;
			}

			input {
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				-webkit-box-shadow: 1px 1px 1px #555;
				background: url(overlay.png) repeat-x 
center #26B026;
				color: white;
				text-shadow: #000 0px 1px 1px;
				font-size: 20px;
				font-family: Helvetica, sans-serif;
				font-weight: bold;
				width: 296px;
				height: 40px;
				border: none;
			}
		</style>
	</head>
	<body>
		<div id="wrapper">
			<h1>Captive Portal</h1>
			<h2>Legalese</h2>
			<div class="content">
				<h3>Section Title</h3>
				<p>Jibberish yadda yadda yadda.</p>
				<h3>Next Section</h3>
				<p>Some more stuff, filler text, 
etc...</p>
			</div><!-- content -->
			<form action="http://10.0.0.1" method="get">
				<input type="hidden" name="add" 
value="">
				<input type="submit" value="I Accept"/>
			</form>
		</div><!-- wrapper -->
	</body>
</html>
  <?php
} else {
	if ($_SERVER['REMOTE_ADDR']=="10.10.10.1") die("Access denied");
	$mac = exec("arp -a ".$_SERVER['REMOTE_ADDR']);
	preg_match('/..:..:..:..:..:../',$mac , $matches);
	@$mac = $matches[0];
	if (!isset($mac)) die("ARP failed");
	file_put_contents("allowed",$mac."\n",FILE_APPEND + LOCK_EX);
	exec("sudo iptables -t nat -I PREROUTING -m mac --mac-source $mac -j ACCEPT -v");
	exec("sudo iptables -I FORWARD -m mac --mac-source $mac -j ACCEPT -v");
	exec("sudo /usr/local/sbin/conntrack -D --orig-src ".$_SERVER['REMOTE_ADDR']);
	sleep(1);
	header("location:http://www.google.com");
}

?>
