<?php
require_once("vars.php");
function mysqlconnect(){
	$var = getvars();
	if($con = mysqli_connect($var['host'],$var['username'],$var['password'],$var['dbname'],$var['port'])){
		return $con;
	}else{
		return false;
	}
}
function getsurveys(){
	$teks = "DAFTAR 10 PELANGGAN SURVEY : \n\n";
	if($con = mysqlconnect()){
		$sql = "select a.id,c.name,";
		$sql.= "case d.resume when '0' then 'Belum ada kesimpulan' ";
		$sql.= " when '1' then 'Dapat dilaksanakan' ";
		$sql.= " when '2' then 'Dapat dilaksanakan dengan syarat' ";
		$sql.= " when '3' then 'Tidak dapat dilaksanakan ' end status ";
		$sql.= "from survey_sites a ";
		$sql.= "left outer join client_sites b on b.id=a.client_site_id ";
		$sql.= "left outer join clients c on c.id=b.client_id ";
		$sql.= "left outer join survey_requests d on d.id=a.survey_request_id ";
		$sql.= "order by a.id desc ";
		$sql.= "limit 0,10";
		$q = $con->query($sql);
		$c = 1;
		while($row = $q->fetch_assoc()){
			$teks.= $c . ". " . $row['name'] . "(".$row["status"].")\n";
			$c = $c+1;
		}
		$teks.="\n Silakan tekan tombol dibawah : \n";
		return $teks;
		mysqli_close($con);
	}else{
		echo "connection cannot be established\n";
	}
}
function getclientsarray(){
        $teks = "Pelanggan Survey : \n";
	if( $con = mysqlconnect()){
                $sql = "select a.id,c.name from survey_sites a ";
                $sql.= "left outer join client_sites b on b.id=a.client_site_id ";
                $sql.= "left outer join clients c on c.id=b.client_id order by a.id desc limit 0,10";
                $q = $con->query($sql);
		$arr = array();
		$c = 1;
                while($row = $q->fetch_assoc()){
                        $teks.= $row['name'] . "\n";
			array_push($arr,$c . "" . $row['name']);
			$c=$c+1;
                }
        return $arr;
        mysqli_close($con);
        }else{
                echo "connection cannot be established\n";
        }
}
function gettickets(){
        $teks = "10 TICKET TERAKHIR: \n\n";
        if( $con = mysqlconnect()){
                $sql = "select a.id,a.kdticket,case a.requesttype when 'pelanggan' then c.name when 'backbone' then d.name when 'datacenter' ";
		$sql.= "then e.name when 'btstower' then f.name when 'core' then g.name when 'ptp' then h.name when 'ap' then i.name end name, ";
		$sql.= "case a.status when '0' then 'Open' when '1' then 'Closed' end status ";
		$sql.= "from tickets a ";
                $sql.= "left outer join client_sites b on b.id=a.client_site_id ";
                $sql.= "left outer join clients c on c.id=b.client_id ";
		$sql.= "left outer join backbones d on d.id=a.backbone_id ";
		$sql.= "left outer join datacenters e on e.id=a.datacenter_id ";
		$sql.= "left outer join btstowers f on f.id=a.btstower_id ";
		$sql.= "left outer join cores g on g.id=a.core_id ";
		$sql.= "left outer join ptps h on h.id=a.ptp_id ";
		$sql.= "left outer join aps i on i.id=a.ap_id ";
		$sql.= "order by a.id desc ";
		$sql.= "limit 0,10 ";
                $q = $con->query($sql);
		$c = 1;
                while($row = $q->fetch_assoc()){
                        $teks.= $c . "." .$row['kdticket'] . "-" . $row['name'] . "(" . $row["status"]. ")" . "\n";
                        array_push($arr,$c . "" .$row['kdticket'] . "-" . $row['name'] . "(" . $row['status'].")");
                        $c=$c+1;
                 }
	$teks.= "\n";
	$teks.= "Gunakan syntax /ticket <kdticket> untuk mengetahui detail ticket \n\n";
        return $teks;
        mysqli_close($con);
        }else{
                echo "connection cannot be established\n";
        }
}
function getticket($kdticket){
        $teks = "KETERANGAN TICKET ".$kdticket.": \n\n";
        if( $con = mysqlconnect()){
                $sql = "select a.id,a.kdticket,case a.requesttype when 'pelanggan' then c.name when 'backbone' then d.name when 'datacenter' ";
                $sql.= "then e.name when 'btstower' then f.name when 'core' then g.name when 'ptp' then h.name when 'ap' then i.name end name, ";
                $sql.= "case a.status when '0' then 'Open' when '1' then 'Closed' end status,a.solution,a.cause ";
                $sql.= "from tickets a ";
                $sql.= "left outer join client_sites b on b.id=a.client_site_id ";
                $sql.= "left outer join clients c on c.id=b.client_id ";
                $sql.= "left outer join backbones d on d.id=a.backbone_id ";
                $sql.= "left outer join datacenters e on e.id=a.datacenter_id ";
                $sql.= "left outer join btstowers f on f.id=a.btstower_id ";
                $sql.= "left outer join cores g on g.id=a.core_id ";
                $sql.= "left outer join ptps h on h.id=a.ptp_id ";
                $sql.= "left outer join aps i on i.id=a.ap_id ";
                $sql.= " ";
                $sql.= "where kdticket='".$kdticket."' ";
                $q = $con->query($sql);
		$teks = "";
                while($row = $q->fetch_assoc()){
                        $teks.= "KODE " .$row['kdticket'] . "\n NAMA:" . $row['name'] . " (" . $row["status"]. ")" . "\n";
			$teks.= "PENYEBAB:\n";
			$teks.= "".$row['cause']."\n";
			$teks.= "SOLUSI:\n";
			$teks.= "".$row['solution']."\n";
                 }
        return $teks;
        mysqli_close($con);
        }else{
                echo "connection cannot be established\n";
        }
}
function getticketsolution(){
        $teks = "10 TICKET TERAKHIR: \n\n";
        if( $con = mysqlconnect()){
                $sql = "select a.id,a.kdticket,case a.requesttype when 'pelanggan' then c.name when 'backbone' then d.name when 'datacenter' ";
                $sql.= "then e.name when 'btstower' then f.name when 'core' then g.name when 'ptp' then h.name when 'ap' then i.name end name, ";
                $sql.= "case a.status when '0' then 'Open' when '1' then 'Closed' end status ";
                $sql.= "from tickets a ";
                $sql.= "left outer join client_sites b on b.id=a.client_site_id ";
                $sql.= "left outer join clients c on c.id=b.client_id ";
                $sql.= "left outer join backbones d on d.id=a.backbone_id ";
                $sql.= "left outer join datacenters e on e.id=a.datacenter_id ";
                $sql.= "left outer join btstowers f on f.id=a.btstower_id ";
                $sql.= "left outer join cores g on g.id=a.core_id ";
                $sql.= "left outer join ptps h on h.id=a.ptp_id ";
                $sql.= "left outer join aps i on i.id=a.ap_id ";
                $sql.= "order by a.id desc ";
                $sql.= "limit 0,10 ";
                $q = $con->query($sql);
                $c = 1;
		$arr1 = array();
		$arr2 = array();
                $arr3 = array();
                $arr4 = array();
                $arr5 = array();
                while($row = $q->fetch_assoc()){
                        $teks.= $c . "." .$row['name'] . "(" . $row["status"]. ")" . "\n";
			if($c<3){
				array_push($arr1,"/ticket " . $row['kdticket']."");
			}elseif($c<5){
				array_push($arr2,"/ticket " . $row['kdticket']."");
                        }elseif($c<7){
                                array_push($arr3,"/ticket " . $row['kdticket']."");
                        }elseif($c<9){
                                array_push($arr4,"/ticket " . $row['kdticket']."");
			}else{
				array_push($arr5,"/ticket " . $row['kdticket']."");
			}
                        $c=$c+1;
                 }
        return array($arr1,$arr2,$arr3,$arr4,$arr5);
        mysqli_close($con);
        }else{
               echo "connection cannot be established\n";
	}
}
function issent($updateid){
	$con = mysqlconnect();
	$sql = "select * from telegram where updateid=".$updateid;
	echo $sql;
	$q = $con->query($sql);
	if($q->num_rows>0){
		return true;
	}
	return false;
}
function setmessagesent($updateid){
	$con = mysqlconnect();
	$sql = "insert into telegram (updateid) values (".$updateid.")";
	$q = $con->query($sql);
	mysqli_close($con);
	return $sql;
}
function getlastid(){
	$con = mysqlconnect();
	$sql = "select max(updateid)updateid from telegram ";
	$q = $con->query($sql);
	$row = $q->fetch_assoc();
	return	 $row['updateid'];
}
?>
