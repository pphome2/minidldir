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


# functions


function dirnametostore($n) {
	$n=str_replace(' ','_',$n);
	$n=str_replace('\'','(',$n);
	return($n);
}


function dirlist($dir) {
	global $DM_CONFIG_DIR;

    $result=array();
    $cdir=scandir($dir);
    foreach ($cdir as $key => $value){
		if (!in_array($value,array(".","..",$DF_CONFIG_DIR))){
			if (substr($value,0,1)<>"."){
				$result[]=$value;
			}
		}
	}
	return($result);
}

function toascii($str) {
	#$clean=preg_replace("/[^A-Za-z0-9\_\-\.]/",'',$str);
	$clean=preg_replace("/[&'\ \"]/","_",$str);
	#echo($clean."?");
	return($clean);
}

function fileup($target_dir){
	global $_POST,$_FILES;
	$ret=FALSE;
	$target_file=basename($_FILES["fileupload"]["name"]);
	if ($target_file<>""){
		$target_file=toascii($target_file);
		if ($target_dir<>""){
			$target_file=$target_dir."/".$target_file;
		}
		$c=$_FILES["fileupload"]["tmp_name"];
		if (move_uploaded_file($_FILES["fileupload"]["tmp_name"],$target_file)) {
			$ret=TRUE;
		}
	}
	return($ret);
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

	# main
	echo("<section id=message>");

	# if submit button

	if (isset($_POST["submitall"])){
		# file upload
		if (isset($_FILES["fileupload"])) {
			if (basename($_FILES["fileupload"]["name"])<>""){
				$dir=$DF_DIR."/".$_POST["sect"];
				$ok=fileup($dir);
				if ($ok){
					mess_ok($L_FILEUP." - ".$L_OK.".");
				}else{
					mess_error($L_FILEUP." - ".$dir." - ".$L_ERROR.".");
				}
			}
		}
		# delete file(s)
		if (isset($_POST["delfile"])) {
			foreach ($_POST["delfile"] as $fname){
				$fname=vinput($fname);
				if ($fname<>""){
					$fd=$fname;
					#echo($fd."<br />");
					if (unlink($fd)){
						mess_ok($L_FILEDELETE." - ".$L_OK.".");
					}else{
						mess_error($L_FILEDELETE." - ".$fd." - ".$L_ERROR.".");
					}
				}
			}
		}
		# create section
		if (isset($_POST["seccre"])) {
			$fn=vinput($_POST["seccre"]);
			if ($fn<>""){
				$fn=$DF_DIR."/".$fn;
				$fn=dirnametostore($fn);
				if (mkdir($fn)){
					mess_ok($L_SECTIONCREATE." - ".$L_OK.".");
				}else{
					mess_error($L_SECTIONCREATE." - ".$fn." - ".$L_ERROR.".");
				}
			}
		}
		# delete section
		if (isset($_POST["secdel"])) {
			$fn=vinput($_POST["secdel"]);
			if ($fn<>""){
				$fn=$DF_DIR."/".$fn;
				if (is_dir($fn)) {
					$objects = scandir($fn);
					foreach ($objects as $object) {
						if ($object != "." && $object != "..") {
							unlink($fn."/".$object);
						}
					}
				}
				if (rmdir($fn)){
					mess_ok($L_SECTIONDELETE." - ".$L_OK.".");
				}else{
					mess_error($L_SECTIONDELETE." - ".$fn." - ".$L_ERROR.".");
				}
			}
		}
		#echo("$p1 - $p2");
	}
	echo("</section>");
	
	$d=dirlist($DF_DIR);

	# form tabs
	echo('
		<div class="containerbox">
		<div class="card-header-tab">
			<button id="card1button" class="card-button tablinks active" onclick="opentab(event, \'tfup\')" id=defaultOpen>'.$L_FILESELECT.'</button>
			<button class="card-button tablinks" onclick="opentab(event, \'tsecnew\')">'.$L_SECTIONCREATE.'</button>
			<button class="card-button tablinks" onclick="opentab(event, \'tsecdel\')">'.$L_SECTIONDELETE.'</button>
			<button class="card-button tablinks" onclick="opentab(event, \'tdocdel\')">'.$L_DOCDELETE.'</button>
		</div>
	');
	
	
	
	# form: upload
	echo("<div id=\"tfup\" class=\"card-body\" style='display:nnone;'>");
		echo("<h2>$L_FILEUP</h2>");
		echo("<section id=form1>");
		echo("<form id=1 method=post enctype=multipart/form-data>");
		echo("    <input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("    <input type='hidden' name='utime' id='passwordh' value='$utime'>");
		echo("<select name=sect id=sect style='padding-top:20px;'>");
		$db=count($d);
		for ($i=0;$i<$db;$i++){
			$dn=$DF_DIR."/".$d[$i];
			if (is_dir($dn)>0){
				echo("<option>$d[$i]");
			}
		}
		echo("</select>");
		echo("<div class=spaceline></div>");
		echo("<input type=file name=fileupload id=fileupload class=inputfile style='padding-top:20px;'>");
		echo("<label for=fileupload>$L_FILESELECT</label>");
		echo("<input type=submit id=submitall name=submitall class=card-button value=$L_BUTTON_ALL>");
		echo("</form>");
		echo("</section>");
	echo("</div>");
	
	# form: folder create
	echo("<div id=\"tsecnew\" class=\"card-body\"  style='display:none;'>");
		echo("<h2>$L_SECTIONCREATE</h2>");
		echo("<section id=form1>");
		echo("<form id=2 method=post enctype=multipart/form-data>");
		echo("    <input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("    <input type='hidden' name='utime' id='passwordh' value='$utime'>");
		echo("<label for=userpass>$L_CREATE : </label>");
		echo("<input name=seccre id=seccre type=text>");
		echo("<input type=submit id=submitall name=submitall class=card-button value=$L_BUTTON_ALL>");
		echo("</form>");
		echo("</section>");
	echo("</div>");
	
	# form: folder delete
	echo("<div id=\"tsecdel\" class=\"card-body\" style='display:none;'>");
		echo("<h2>$L_SECTIONDELETE</h2>");
		echo("<section id=form1>");
		echo("<form id=3 method=post enctype=multipart/form-data>");
		echo("    <input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("    <input type='hidden' name='utime' id='passwordh' value='$utime'>");
		echo("<select name=secdel id=secdel>");
		echo("<option>");
		$db=count($d);
		for ($i=0;$i<$db;$i++){
			$dn=$DF_DIR."/".$d[$i];
			if (is_dir($dn)>0){
				echo("<option>$d[$i]");
			}
		}
	
		echo("</select>");
		echo("<input type=submit id=submitall name=submitall class=card-button value=$L_BUTTON_ALL>");
		echo("</form>");
		echo("</section>");
	echo("</div>");
	
	# form: file delete
	echo("<div id=\"tdocdel\" class=\"card-body\" style='display:none;'>");
		echo("<h2>$L_DELETE</h2>");
		echo("<section id=form1>");
		echo("<form id=4 method=post enctype=multipart/form-data>");
		echo("    <input type='hidden' name='passwordh' id='passwordh' value='$passw'>");
		echo("    <input type='hidden' name='utime' id='passwordh' value='$utime'>");
		$db=count($d);
		for ($i=0;$i<$db;$i++){
			$dn=$DF_DIR."/".$d[$i];
			if (is_dir($dn)){
				$d2=dirlist($dn);
				if (count($d2)>0){
					#echo("<section id=s1>");
					echo("<h2>$d[$i]</h2>");
					echo("<div class=panel>");
					echo("<section id=s2>");
					echo("<section id=formx style='padding-left:40px;'>");
					$db2=count($d2);
					for ($k=0;$k<$db2;$k++){
						$fn=$DF_DIR."/".$d[$i]."/".$d2[$k];
						echo('<p>');
						echo("<input type=checkbox name=delfile[] id=delfile value=\"$fn\"><a style='text-decoration:none;' href=$fn>$d2[$k]</a>");
						echo('</p>');
					}
					echo("</section>");
					echo("</section>");
				echo("</div>");
				#echo("</section>");
			}
		}
	}
	echo("<input type=submit id=submitall name=submitall class=card-button value=$L_BUTTON_ALL>");
	echo("</form>");
	echo("</section>");
	echo("</div>");

	echo("</section>");

	echo("</div>");

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
