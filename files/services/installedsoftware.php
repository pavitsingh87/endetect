<?php
include("connection.php");
$enduserid = base64_decode($_GET["eid"]);
$getquery = $conn->query("select * from enduserinstalledsoftware where enduserid='".$enduserid."'");
if($getquery->num_rows>0)
{
	ob_start();
	?>
	<div class="container">
	  <h2>Installed Software</h2>
	  <table class="table" style="width: 50%;">
	    <thead>
	      <tr>
	        <th>Display Name</th>
	        <th>Publisher</th>
	        <th>Estimated Size</th>
	        <th>Install Date</th>
	        <th>Comments</th>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php while($row = $getquery->fetch_assoc()) { ?>
	      <tr>
	        <td><?php echo $row["displayname"]; ?></td>
	        <td><?php echo $row["publisher"]; ?></td>
	        <td><?php echo $row["estimatedsize"]; ?></td>
	        <td><?php echo $row["installdate"]; ?></td>
	        <td><?php echo $row["comments"]; ?></td>
	      </tr>
	     <?php } ?>
	    </tbody>
	  </table>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
}
?>