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


function formatBytes($size, $precision=2){
    $base=log($size, 1024);
    $suffixes=array('', 'K', 'M', 'G', 'T');
    return round(pow(1024,$base-floor($base)),$precision).' '.$suffixes[floor($base)];
}

function filetable($dir){
    global $DF_FILEEXT,$DF_LANG,$DF_TEXTFILE_EXT,$DF_DOWNLOAD_TEXT,$table;

    $files=scandir($dir);
    asort($files);
    $fdb=0;
    foreach ($files as $entry) {
        if ($entry!="." && $entry!="..") {
			$dirn=$dir.'/'.$entry;
			if (is_dir($dirn)){
				echo("<br /><br />");
				echo("<b>$entry</b>");
				echo("<br /><br />");
				echo("<center>");
				echo("<table class='df_table'>");
				echo("<tr class='df_trh'>");
				echo("<th class='df_th1'>$DF_LANG[0]</th>");
				echo("<th class='df_th2'>$DF_LANG[1]</th>");
				echo("<th class='df_th2'>$DF_LANG[2]</th>");
				echo("</tr>");
				$table=true;
				filetable($dirn);
				echo("</table>");
				echo("</center>");
				$table=false;
			}else{
				if (!$table){
					$dn=explode('/',$dir);
					$s=count($dn)-1;
					$dirx=$dn[$s];
					echo("<br /><br />");
					echo("<b>$dirx</b>");
					echo("<br /><br />");
					echo("<center>");
					echo("<table class='df_table'>");
					echo("<tr class='df_trh'>");
					echo("<th class='df_th1'>$L_TABLE_HEAD[0]</th>");
					echo("<th class='df_th2'>$L_TABLE_HEAD[1]</th>");
					echo("<th class='df_th2'>$L_TABLE_HEAD[2]</th>");
					echo("</tr>");
					$table=true;
				}
			}
            $fileext=explode('.',$entry);
            $fileext_name=$fileext[count($fileext)-1];
            $fileext_name2='.'.$fileext_name;
            if ((in_array($fileext_name, $DF_FILEEXT))or(in_array($fileext_name2, $DF_FILEEXT))){
                echo("<tr class='df_tr'>");
                $fileext_name=strtoupper($fileext_name);
                echo("<td class='df_td'><span class='df_tds'>[$fileext_name]</span> ");
                echo("<a href='$dir/$entry' target='$target' class='df_tda'>$entry</a>");
                echo(" ");
                #echo("<br />");
                #echo("<br />");
                #$filetext=$dir.'/'.$entry.$DF_TEXTFILE_EXT;
                #if (file_exists($filetext)){
                #    echo(file_get_contents($filetext)."<br />");
                #    echo("<br />");
                #    echo("<br />");
                #}
                echo("<a href='$dir/$entry' download class='df_tda2' onclick='delrow(this);'>$L_DOWNLOAD_TEXT</a>");
                echo("<br />");
                echo("<br />");
                echo("</td>");
                $m=filectime($dir.'/'.$entry);
                $m=gmdate("Y.m.d", $m);
                echo("<td class='df_td2'>$m</td>");
                $m=filesize($dir.'/'.$entry);
                $m=formatBytes($m);
                echo("<td class='df_td2'>$m</td>");
                echo("</tr>");
            }
        }
    }
            if ($table){
				echo("</table>");
				echo("</center>");
			}

}


if ($DF_LINK_TARGET_NEW_WINDOW){
    $target="_blank";
}else{
    $target="";
}

echo("<br /><br />");

filetable($DF_DIR);

echo("<br /><br />");




if (file_exists("$DF_JS_END")){
	include("$DF_JS_END");
}
if (file_exists($DF_FOOTER)){
	include("$DF_FOOTER");
}

?>
