<?php
include("connection.php");
$searchstr = $_REQUEST["search"];
$i=1;
$sql_res=$conn->query("select id,searchtxt from searchin where active=1");
while($row=$sql_res->fetch_assoc())
{
	$searchtxt = strip_tags($searchstr." ".$row["searchtxt"]);
	?>
	<div class="show" align="left" >
		<div id="namesearchANDENDETECTAND<?=$row['id']?>" ><?php echo $searchtxt;?>
		</div>
	</div>
	<?php 
	
}
$conn->close();
?>