<?php

 #
 # MiniApp - empty app
 #
 # info: main folder copyright file
 #
 #


if (file_exists("config/config.php")){
	include("config/config.php");
}
if (file_exists("config/$MA_LANGFILE")){
	include("config/$MA_LANGFILE");
}


function vinput($d) {
    $d=trim($d);
    $d=stripslashes($d);
    $d=strip_tags($d);
    $d=htmlspecialchars($d);
    return $d;
}


function vinputtags($d) {
    $d=trim($d);
    $d=stripslashes($d);
    $d=htmlspecialchars($d);
    return $d;
}


$utime=time();
$loggedin=FALSE;
$passw="";

if (isset($_POST["password"])){
	$passw=md5($_POST["password"]);
	$passw=vinput($passw);
	if ($passw==$MA_PASS){
		$loggedin=TRUE;
	}
}

if (isset($_POST["passwordh"])){
	$passw=$_POST["passwordh"];
	$passw=vinput($passw);
	if ($passw==$MA_PASS){
		if (isset($_POST["utime"])){
			$outime=$_POST["utime"];
			$outime=vinput($outime);
			$utime2=$utime-$outime;
			if ($utime2<$LOGIN_TIMEOUT){
				$loggedin=TRUE;
			}
		}else{
			$loggedin=TRUE;
		}
	}
}



echo("<!DOCTYPE HTML>");
echo("<html><head>");
echo("<title>$MA_SITENAME</title>");
echo("<meta charset=\"utf-8\" />");
echo("<meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\">");
echo("<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />");
echo("<link rel=\"icon\" href=\"favicon.png\">");
echo("<link rel=\"shortcut icon\" type=\"image/png\" href=\"favicon.png\" />");
echo("</head>");
echo("<style>");
include("$MA_CSS2");
echo("</style>");
echo("<body>");

echo("<a onclick=\"window.history.back();\">");



if ($loggedin){
	echo($L_BUTTON_PRINT);
}else{
	echo("---");
}

echo("</a></body></html>");

?>
