
<?php 
include("connection.php");

$searchinquery = $conn->query("select * from searchin where active=1 order by positiondiv asc");
?>
<select name="searchinobj" id="searchinobj" class="form-control" onchange="searchinchn(this.value)" style="width:100%;font-size:10px;">
<?php 
while($exesearchin = $searchinquery->fetch_assoc())
{ 
	?>
	<option value="<?php echo $exesearchin['id']?>" <?php if($exesearchin['id']==$_REQUEST['optionsearch']) { echo "selected";  }?>><?php echo ucfirst($exesearchin['searchtxt']);?></option>
	<?php 
}
?>
</select>
