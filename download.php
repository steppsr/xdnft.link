<?php
/*
	Download file
		filename will be from the "fn" variable in the GET string.
		content is stored in the session in variable named from the "cn" variable in the GET string.

		Example:
			download.php?fn=owner_wallets.csv&cn=owner-wallets
*/
session_start();
if(isset($_GET["cn"]))
{

	// get vars from query string
	$cn = strip_tags($_GET["cn"]);
	$content = $_SESSION[$cn];
	$temp_file = tmpfile();
	fwrite($temp_file, $content);
	$temp_file_path = stream_get_meta_data($temp_file)['uri'];

	// setup headers
	header("Content-Type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=list.csv");
	header("Content-Length: ". filesize($temp_file_path));

//	header("Content-Description: File Transfer");
//	header("Expires: 0");
//	header("Cache-Control: must-revalidate");
//	header("Pragma: public");
//	flush();
	readfile($temp_file_path);
	fclose($temp_file);
	exit;
}


?>