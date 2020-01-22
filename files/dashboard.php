<?php
$title="Settings | EnDetect";
include("commonfunctions.php");
checkOwnerSession();
include("header.php");
?>
<body ng-app="submitExample">
<?php
include("headerbar.php");

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

//echo "<pre>"; print_r($_SESSION);
$ownerId = $_SESSION['ownerid'];
$licResult = $conn->query("SELECT * FROM U_license WHERE owner_id ='". $ownerId ."'");
$licRow = $licResult->fetch_object();

$SD = date("Y-m-01", time());
$ED = date("Y-m-31", time());
$internetUsageResult = $conn->query("SELECT * FROM U_internetusage WHERE endownerid = '$ownerId' AND datesave >= '$SD' AND datesave <= '$ED'");
$interUpload = 0;
$interDownload = 0;
$interTotal = 0;
if($internetUsageResult->num_rows != 0) {
	while($row = $internetUsageResult->fetch_object()) {
		$interUpload += $row->sent;
		$interDownload += $row->received;
	}

	$interTotal = $interUpload + $interDownload;
}

$userDataResult = $conn->query("SELECT * FROM U_endusers WHERE ownerid = '$ownerId' AND deleteuser = '0'");
$fileCopied = 0;
$usbInsert = 0;
$mobileInsert = 0;
if($userDataResult->num_rows != 0) {
	while($row = $userDataResult->fetch_object()) {
		$fileCopied += $row->files_copies;
		$usbInsert += $row->pendriveinsert;
		$mobileInsert += $row->mobileinsert;
	}
}

$internetUsageResult = $conn->query("SELECT procname, SUM(totaltime) AS total FROM U_runningapps WHERE endownerid = '$ownerId'
	AND datesave >= '$SD' AND datesave <= '$ED' GROUP BY procname ORDER BY total DESC LIMIT 5");


$minusOneHour = date('Y-m-d H:i:s', strtotime('-1 hours', time()));
$activeUser = $conn->query("SELECT sno FROM U_endusers WHERE active = 1 AND licenseflag = 1 AND deleteuser = 0
	AND ownerid = '$ownerId' AND lastaccesstime >= '$minusOneHour' ORDER BY lastaccesstime DESC");
$totalActive = $activeUser->num_rows;

$minusEightHour =  date('Y-m-d H:i:s', strtotime('-8 hours', time()));
$lazyUser = $conn->query("SELECT sno FROM U_endusers WHERE active = 1 AND licenseflag = 1 AND deleteuser = 0
	AND ownerid = '$ownerId' AND (lastaccesstime between '$minusEightHour' AND '$minusOneHour') ORDER BY lastaccesstime DESC");
$totalLazy = $lazyUser->num_rows;

$offlineUser = $conn->query("SELECT sno FROM U_endusers WHERE active = 1 AND licenseflag = 1 AND deleteuser = 0
	AND ownerid = '$ownerId' AND lastaccesstime <= '$minusEightHour' ORDER BY lastaccesstime DESC");
$totalOffline = $offlineUser->num_rows;
?>

<div id="content-main" ng-controller="ExampleController">
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Active</div>
				<div class="panel-body"><?php echo $totalActive; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Lazy</div>
				<div class="panel-body"><?php echo $totalLazy; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Offline</div>
				<div class="panel-body"><?php echo $totalOffline; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total License</div>
				<div class="panel-body"><?php echo $licRow->total_lic; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Used License</div>
				<div class="panel-body"><?php echo $licRow->license_used; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Pending License</div>
				<div class="panel-body"><?php echo $licRow->total_lic - $licRow->license_used; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total Screenshot</div>
				<div class="panel-body">???</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Total On Demand Screenshot</div>
				<div class="panel-body">???</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Copied Files</div>
				<div class="panel-body"><?php echo $fileCopied; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">USB Drive Ins</div>
				<div class="panel-body"><?php echo $usbInsert; ?></div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">Mobile Ins</div>
				<div class="panel-body"><?php echo $mobileInsert; ?></div>
			</div>
		</div>


		<div class="col-md-4">
			<table class="table table-bordered">
			    <tr>
			        <th colspan="2" class="text-center">This Month Bandwidth Usage</th>
			    </tr>
				<tr>
			        <td>Upload</td>
			        <td><?php echo formatSizeUnits($interUpload); ?></td>
			    </tr>
			    <tr>
			        <td>Download</td>
			        <td><?php echo formatSizeUnits($interDownload); ?></td>
			    </tr>
			    <tr>
			        <td>Total</td>
			        <td><?php echo formatSizeUnits($interTotal); ?></td>
			    </tr>
			</table>
		</div>
		<div class="col-md-4">
			<table class="table table-bordered">
			    <tr>
			        <th class="text-center">This Month Top 5 Running Apps</th>
			    </tr>
				<?php if($internetUsageResult->num_rows != 0) {
					while($row = $internetUsageResult->fetch_object()) { ?>
						<tr><td><?php echo $row->procname; ?></td></tr>
				<?php } } else { ?>
					<tr><td>NO DATA</td></tr>
				<?php } ?>
			</table>
		</div>




	</div>

	<div id="pen1">

	</div>
	<div id="pen2">




	</div>
</div>
<?php include("footer.php")?>
