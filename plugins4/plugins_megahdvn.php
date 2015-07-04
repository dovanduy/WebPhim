<?php
$username = "ketvang";
$password = "matlasat";

$getacc = $_POST['getacc'];
if($getacc=="true"){
	$txtacc = "&u=".$username."&p=".$password."&";
	echo $txtacc;
}
?> 