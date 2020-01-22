<?php
$title = "Installed Software | EnDetect";
if(isset($_GET['eid'])) {
	$enduserid = base64_decode($_GET["eid"]);
	//$_REQUEST['enduserid'] = $enduserid;
} else {
	$enduserid = '';
}

include_once("commonfunctions.php");
checkOwnerSession();
include_once("header.php");
?>
<body ng-app="submitExample" id="pavdescription1">
<?php include_once("headerbar.php"); ?>
<div style="clear:both"></div>
<div id="content-main" ng-controller="ExampleController">
	<div id="pen1">
		<?php
		userprofile_account($enduserid);
		leftbar_withoutlive_account($enduserid);
		?>
		<br> <br>
	</div>
	<div id="pen2">
		<div id="pen2-left">
			<div class="col-md-12">
				<h3>Installed Software</h3>
			</div>
	 		<div class="clearfix"></div>
			<hr>
			<?php
			$getquery = $conn->query("SELECT * FROM enduserinstalledsoftware WHERE enduserid = '".$enduserid."' ORDER BY displayname ASC");
			if($getquery->num_rows != 0) {
				$arrLoop = array();
				while($row = $getquery->fetch_assoc()) {
					$pos_1 = strpos($row["displayname"], "Microsoft");
					$pos_2 = strpos($row["displayname"], "Driver");

					if ($pos_1 !== false) { //Microsoft
						$arrLoop['microsoft'][] = array($row["displayname"], $row["publisher"], $row["estimatedsize"], $row["installdate"]);
					} else {
						if ($pos_2 !== false) { //Driver
							$arrLoop['driver'][] = array($row["displayname"], $row["publisher"], $row["estimatedsize"], $row["installdate"]);
						} else { //Other
							$arrLoop['other'][] = array($row["displayname"], $row["publisher"], $row["estimatedsize"], $row["installdate"]);
						}
					}
				} //while closed

				$thead = '<thead><tr>
							<th style="width:45%">Software</th>
							<th style="width:20%">Publisher</th>
							<th style="width:20%">Estimated Size (MB)</th>
							<th style="width:15%">Install Date</th>
						</tr></thead>';
				?>

				<ul class="nav nav-tabs" style="margin-top:20px;">
					<li class="active"><a data-toggle="tab" href="#other">All</a></li>
					<li><a data-toggle="tab" href="#microsoft">Microsoft</a></li>
					<li><a data-toggle="tab" href="#driver">Driver</a></li>
				</ul>

				<div class="tab-content">
					<?php foreach ($arrLoop as $key => $val) { ?>
						<div id="<?php echo $key; ?>" class="tab-pane fade in <?php if($key == 'other') {echo 'active';} ?>">
							<table class="table table-striped">
								<?php echo $thead; ?>
								<tbody>
									<?php foreach ($val as $row) { ?>
									<tr>
										<td><?php echo $row[0]; ?></td>
										<td><?php echo $row[1]; ?></td>
										<td><?php
											if($row[2] != "0") {
												echo $row[2];
											} else {
												echo "-";
											}
										?></td>
										<td><?php
											if($row[3] != "0000-00-00") {
												echo date("d-m-Y", strtotime($row[3]));
											} else {
												echo "-";
											}
										?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } //foreach closed ?>

				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php include_once("footer.php"); ?>
