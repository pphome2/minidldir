<?php

#
# Full image landing page
#
# 2019. WSWDTeam GPLv3
#
#


	$FL_LANG=array('Név','Dátum','Méret');
	$FL_FILEEXT='.pdf';
	$FL_FILEEXT_NAME='PDF';
?>


<style type="text/css">
  .fl_table {
    width:90%;
  }
  
  .fl_trh {
    background-color:lightblue;
  }
  
  .fl_th1 {
    padding:5px;
    color:#a7a8a8;
    width:60%;
  }
  
  .fl_th2 {
    padding:5px;
    color:#a7a8a8;
    width:20%;
  }
  
  .fl_tr:nth-child(even){
    background-color:white;
  }

  .fl_tr:nth-child(odd) {
    background-color:#f2f2f2;
  }
  
  .fl_td {
    color:#808080;
    text-align:left;
  } 
  
  .fl_td2 {
    color:#808080;
    text-align:center;
  }
  
  .fl_tds {
    color:red;
  }
  
  .fl_tda {
    text-decoration:none;
    color:#808080;'
  }


</style>


<?php

function formatBytes($size, $precision=2){
	$base=log($size, 1024);
	$suffixes=array('', 'K', 'M', 'G', 'T');   
	return round(pow(1024,$base-floor($base)),$precision).' '.$suffixes[floor($base)];
}


echo("<center>");
echo("<table class='fl_table'>");
echo("<tr class='fl_trh'>");
echo("<th class='fl_th1'>$FL_LANG[0]</th>");
echo("<th class='fl_th2'>$FL_LANG[1]</th>");
echo("<th class='fl_th2'>$FL_LANG[2]</th>");
echo("</tr>");


$files=scandir('.');
asort($files);
foreach ($files as $entry) {
	if ($entry!="." && $entry!="..") {
		if (strpos($entry, $FL_FILEEXT)){
			echo("<tr class='fl_tr'>");
			echo("<td class='fl_td'><span class='fl_tds'>[$FL_FILEEXT_NAME]</span> ");
			echo("<a href='./$entry' target='_blank' class='fl_tda'>$entry</a></td>");
			$m=filectime('./'.$entry);
			$m=gmdate("Y.m.d", $m);
			echo("<td class='fl_td2'>$m</td>");
			$m=filesize('./'.$entry);
			$m=formatBytes($m);
			echo("<td class='fl_td2'>$m</td>");
			echo("</tr>");
		}
	}
}

echo("</table>");
echo("</center>");

?>



