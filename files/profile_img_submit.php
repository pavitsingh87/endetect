<?php
if(isset($_POST["image"])) {
    include_once("connection.php");

	$data = $_POST["image"];
	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$data = base64_decode($image_array_2[1]);
	$imageName = time() .".png";
    $imgPath = "uploads/". $imageName;
	file_put_contents($imgPath, $data);
	echo '<img src="'.$imgPath.'" class="img-thumbnail">';

    $eid = $_POST['eid'];
    $conn->query("UPDATE U_endusers SET profilepic = '". $imgPath ."' WHERE sno = $eid ");
}
?>
