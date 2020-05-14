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

echo('
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo($DF_SITENAME); ?></title>
		<meta charset="utf-8" />
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" href="favicon.png">
		<link rel="shortcut icon" type="image/png" href="favicon.png" />
	</head>
	<style>
');

if (file_exists("$DF_CSS")){
	include("$DF_CSS");
}

echo('</style><body>');
echo('<div class=dl-content>');

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


function formatBytes($size, $precision=2){
    $base=log($size, 1024);
    $suffixes=array('', 'K', 'M', 'G', 'T');
    return round(pow(1024,$base-floor($base)),$precision).' '.$suffixes[floor($base)];
}

function filetable($dir){
	global $DF_FILEEXT,$DF_LANG,$DF_TEXTFILE_EXT,$L_DOWNLOAD_TEXT,$L_TABLE_HEAD,$table,$dirnum;

	$files=scandir($dir);
	asort($files);
	$fdb=0;
	$dirnum++;
	foreach ($files as $entry) {
		if ($entry!="." && $entry!=".." && $entry!="lost+found") {
			$dirn=$dir.'/'.$entry;
			if (is_dir($dirn)){
				echo('
					<div class="card">
						<div class="card-header" id="cardheader'.$dirnum.'">
							<span onclick="cardclose(cardbody'.$dirnum.',cardright'.$dirnum.')" class="topleftmenu1">
								'.$entry.'
							</span>
							<span onclick="cardclose(cardbody'.$dirnum.',cardright'.$dirnum.')" class="topright" id="cardright'.$dirnum.'">
								+
							</span>
						</div>
						<div class="card-body" id="cardbody'.$dirnum.'" style="display:none;">
				');
				echo("<table class='mf_table_full'>");
				echo("<tr class='mf_trh'>");
				echo("<th class='mf_th1'>$L_TABLE_HEAD[0]</th>");
				echo("<th class='mf_th2'>$L_TABLE_HEAD[1]</th>");
				echo("<th class='mf_th2'>$L_TABLE_HEAD[2]</th>");
				echo("</tr>");
				$table=true;
				filetable($dirn);
				$table=false;
			}else{
				if (!$table){
					$dn=explode('/',$dir);
					$s=count($dn)-1;
					$dirx=$dn[$s];
					echo('
						<div class="card">
							<div class=card-header>
								<span onclick="cardclose(cardbody'.$dirnum.',cardright'.$dirnum.')" class="topleftmenu1">
								'.$entry.'
							</span>
							<span onclick="cardclose(cardbody'.$dirnum.',cardright'.$dirnum.')" class="topright" id="cardright'.$dirnum.'">
								+
							</span>
							</div>
							<div class="card-body" id="cardbody'.$dirnum.'" style="display:none;">
					');
					echo("<table class='mf_table_full'>");
					echo("<tr class='mf_trh'>");
					echo("<th class='mf_th1'>$L_TABLE_HEAD[0]</th>");
					echo("<th class='mf_th2'>$L_TABLE_HEAD[1]</th>");
					echo("<th class='mf_th2'>$L_TABLE_HEAD[2]</th>");
					echo("</tr>");
					$table=true;
				}
			}
			$fileext=explode('.',$entry);
			$fileext_name=$fileext[count($fileext)-1];
			$fileext_name2='.'.$fileext_name;
			if ((in_array($fileext_name, $DF_FILEEXT))or(in_array($fileext_name2, $DF_FILEEXT))){
				echo("<tr class='mf_tr'>");
				$fileext_name=strtoupper($fileext_name);
				echo("<td class='mf_td'><span class='mf_tds'>[$fileext_name]</span> ");
				echo("<a href='$dir/$entry' target='$target' class='mf_tda'>$entry</a>");
				echo(" - <a href='$dir/$entry' download class='mf_tda2' onclick='delrow(this);'>$L_DOWNLOAD_TEXT</a>");
				echo("</td>");
				$m=filectime($dir.'/'.$entry);
				$m=gmdate("Y.m.d", $m);
				echo("<td class='mf_td2'>$m</td>");
				$m=filesize($dir.'/'.$entry);
				$m=formatBytes($m);
				echo("<td class='mf_td2'>$m</td>");
				echo("</tr>");
			}
		}
	}
	if ($table){
		echo("</table>");
		echo("</center>");
		echo("</div>");
		echo("</div>");
	}

}


if ($DF_LINK_TARGET_NEW_WINDOW){
    $target="_blank";
}else{
    $target="";
}



filetable($DF_DIR);




if (file_exists("$DF_JS_END")){
	include("$DF_JS_END");
}

echo("</div></body></html>");

?>
