<?php
	require_once("db.php");
	require_once("tg.php");
	$las = getlastid();
	$website = getwebsite();
	$update = file_get_contents($website."/getupdates?offset=".getlastid());
	$printupdate = false;
	if($printupdate){
		echo $update;
	}
	$updateArray = json_decode($update,TRUE);
	$cnt =  count($updateArray["result"]);
	$dcnt = $cnt-1;
	$text = $updateArray["result"][$dcnt]["message"]["text"];
	$chatid = $updateArray["result"][$dcnt]["message"]["chat"]["id"];
	$updateid = $updateArray["result"][$dcnt]["update_id"];
	echo "UPDATE ID : " . $updateid."\n";
	echo "QUERY : ".$website."/getupdates?offset=".getlastid()."\n";
	if(!issent($updateid)){
		exec_tg();
	}
?>
