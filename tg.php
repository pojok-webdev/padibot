<?php
require_once("db.php");
function getkeyboard($type="standard"){
	$kb = "0";
	switch($type){
	case "standard":
		return array(array("/surveys","/tickets"));
	end;
	case "client":
		return getclientsarray();
	end;
	case "listticket":
		return getticketsolution();
	end;
	}
}
function getwebsite(){
	$vars = getvars();
	$tokens = $vars['tokens'];
	$botToken = $tokens["padiapp_bot"];
	$website = "https://api.telegram.org/bot".$botToken;
	return $website;
}
function exec_tg(){
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
	$updateidi = $updateid;
	$arrtext = array();
	$arrtext = explode(" ",$text);
	switch($arrtext[0]){
        case "/help":
		$keyboard = getkeyboard();
                $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
                $reply = json_encode($resp);
                $text1 = "Silakan memilih database yang hendak ditampilkan :";
                exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
        break;
	case "/show":
		$keyboard = getkeyboard();
                $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
                $reply = json_encode($resp);
                $text1 = "Silakan memilih database yang hendak ditampilkan :";
                exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
		echo setmessagesent($updateidi);
	break;
	case "/info":
		$keyboard = getkeyboard("listticket");
		$resp = array("keyboard"=>$keyboard,"resize_keyboard"=>true,"one_time_keyboard"=>true);
		$reply = json_encode($resp);
		exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
	break;
	case "/surveys":
		$keyboard = getkeyboard();
   		$resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
   		$reply = json_encode($resp);
		$text1 = getsurveys();
		$keyboard = array($text1);
		exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
	break;
        case "/tickets":
                $keyboard = getkeyboard("listticket");
                $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
                $reply = json_encode($resp);
                $text1 = gettickets();
                exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
        break;
        case "/ticket":
		$keyboard = getkeyboard();
                $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
                $reply = json_encode($resp);
                $text1 = getticket($arrtext[1]);
                exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
        break;
	case "/menu":
                $keyboard = getkeyboard();
                $resp = array("keyboard" => $keyboard,"resize_keyboard" => true,"one_time_keyboard" => true);
                $reply = json_encode($resp);
                $text1 = "silakan pilih menu pada virtual keyboard";
                exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text=".$text1." &reply_markup=".$reply."'");
                echo setmessagesent($updateidi);
	break;
	default:
		$printhospitality = false;
		$teks = "Perintah yang anda berikan tidak dikenali oleh padiapp_bot ";
		if($printhospitality){
        	        exec("wget ".$website."/sendMessage --post-data 'chat_id=".$chatid."&text= ".$teks . $text." '");
	                echo setmessagesent($updateidi);
		}
	break;
	}
}
?>
