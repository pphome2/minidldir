<?php

 #
 # MiniApp - download dir app
 #
 # info: main folder copyright file
 #
 #

if (file_exists("config/config.php")){
	include("config/config.php");
}

if (file_exists("config/$DF_LANGFILE")){
	include("config/$DF_LANGFILE");
}
if (file_exists("$DF_HEADER")){
	include("$DF_HEADER");
}
if (file_exists("$DF_JS_BEGIN")){
	include("$DF_JS_BEGIN");
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


function mess_error($m){
    echo("<div class='message' style='mmargin:20px;'>
	    <div onclick='this.parentElement.style.display=\"none\"' class='toprightclose'></div>
	    <p style='padding-left:40px;'>$m</p>
	</div>");
}


function mess_ok($m){
    echo("<div class='card'>
	    <div onclick='this.parentElement.style.display=\"none\"' class='toprightclose'></div>
	    <div class=card-header><br /></div>
	    <div class='cardbody' id='cardbody'>
		<p style='padding-left:40px;padding-bottom:20px;'>$m</p>
	    </div>
	</div>");
}




$utime=time();
$loggedin=FALSE;
$passw="";

if (isset($_POST["password"])){
	$passw=md5($_POST["password"]);
	$passw=vinput($passw);
	if ($passw==$DF_PASS){
		$loggedin=TRUE;
	}
}

if (isset($_POST["passwordh"])){
	$passw=$_POST["passwordh"];
	$passw=vinput($passw);
	if ($passw==$DF_PASS){
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





if ($loggedin){
	echo("<form action=$DF_PRINTFILE id=3000 method=post enctype=multipart/form-data>");
	echo("	<input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
	echo("	<input type='hidden' name='utime' id='utime' value='$utime'>");
	echo("	<input class='inputsubmit' type='submit' id='submitprintx' name='submitprintx' value='$L_BUTTON_PRINT'>");
	echo("</form>");

}else{
	# password
	echo("<h1>$L_SITENAME</h1>");
	echo("<div class=spaceline100></div>");
	echo("<form  method='post' enctype='multipart/form-data'>");
	echo("    $L_PASS:");
	echo("    <input type='password' name='password' id='password' autofocus>");
	echo("<div class=spaceline></div>");
	echo("    <input type='submit' value='$L_BUTTON_ALL' name='submit'>");
	echo("</form>");
	echo("<div class=spaceline></div>");
}



if (file_exists("$DF_JS_END")){
	include("$DF_JS_END");
}
if (file_exists($DF_FOOTER)){
	include("$DF_FOOTER");
}

?>
