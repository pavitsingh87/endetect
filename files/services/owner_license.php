<?php 
@session_start();
include("connection.php");
$concatsearchres="";
$licensequery = "select sum(total_lic) as sumlic from U_license where owner_id='".$_SESSION['ownerid']."'";
$runlicense = $conn->query($licensequery);
$runarr = $runlicense->fetch_assoc();
$exelicense = $runarr['sumlic'];

$count_enduser_query = "select count(sno) as countuser from U_endusers where ownerid='".$_SESSION['ownerid']."'";
$runenduser = $conn->query($count_enduser_query);
$runendarr = $runenduser->fetch_assoc();
$exeenduser = $runendarr['countuser'];

$leftlic = ($exelicense-$exeenduser);

$leftper = (($leftlic/$exelicense)*100);

$usedper = (($exeenduser/$exelicense)*100);

$concatsearchres.= '{"sumlic":"'.$exelicense.'","countenduser":"'.$exeenduser.'","leftlic":"'.$leftlic.'","leftper":"'.$leftper.'","usedper":"'.$usedper.'"},';
$concatsearchres=substr($concatsearchres, 0, -1);
$concatsearchres='['.$concatsearchres.']';
# JSON-encode the response
echo $concatsearchres;

$conn->close();
?>