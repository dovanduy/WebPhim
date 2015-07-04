<?php
$list_ip = array(
	"127.0.0.1",		// Local
	"113.184.85.100"	// IP Hosting
	);
$file_listip = "listip.txt";
$fopen_ip = fopen($file_listip, "r");

while ( !feof($fopen_ip) )
	{
		$read_ip = fgets($fopen_ip,50);
		$ip = explode('<nbb>', $read_ip);
		$list_ip[] = $ip[1];
	}
	fclose($fopen_ip);

if ( !in_array($_SERVER['REMOTE_ADDR'], $list_ip) ){ 
        echo "<center>Khong co quyen truy cap</center>";
        exit();
    }
?>