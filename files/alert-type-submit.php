<?php
include_once("connection.php");
$owner_id = $_SESSION['ownerid'];

if(isset($_POST['submit'])) {
    $internet_usage = trim($_POST['internet_usage']);
    $idle_time = $_POST['idle_time'];
    $usb_on = $_POST['usb_on'];

    $data = $conn->query("SELECT id FROM tbl_create_alert2 WHERE owner_id = $owner_id");
    if($data->num_rows != 0) {
        $conn->query("UPDATE tbl_create_alert2 SET
            internet_usage = $internet_usage, idle_time = $idle_time, usb_on = $usb_on
            WHERE owner_id = $owner_id");
    } else {
        $conn->query("INSERT INTO tbl_create_alert2 (owner_id, internet_usage, idle_time, usb_on) VALUES(
            $owner_id, $internet_usage, $idle_time, $usb_on)");
    }

    header("Location: alert-type.php");
}


if(isset($_GET['fun']) && $_GET['fun'] == 'addWord') {
    $alert_type = $_POST['alert_type'];
    $word = trim($_POST['word']);
    $wordType = $_POST['wordType'];
    $wordOption = $_POST['wordOption'];

    $data = $conn->query("SELECT id FROM tbl_create_alert
        WHERE alert_type = $alert_type AND word = '$word' AND word_type = $wordType AND word_option = $wordOption");
    if($data->num_rows != 0) {
        $dataArr = array("status" => 400, "msg" => "Already Exists", "result" => "");
        echo json_encode($dataArr);
    } else {
        $conn->query("INSERT INTO tbl_create_alert (owner_id, alert_type, word, word_type, word_option) VALUES
            ($owner_id, $alert_type, '$word', $wordType, $wordOption)");
        $last_id = $conn->insert_id;

        if($wordType == 1) {
            $wordTypeT = 'Typed';
        } else if($wordType == 2) {
            $wordTypeT = 'Copied';
        } else if($wordType == 3) {
            $wordTypeT = 'Deleted';
        } else {
            $wordTypeT = 0;
        }

        if($wordOption == 1) {
            $wordOptionT = 'Exact Match';
        } else if($wordOption == 2) {
            $wordOptionT = 'Containing';
        } else if($wordOption == 3) {
            $wordOptionT = 'Begins With';
        } else if($wordOption == 4) {
            $wordOptionT = 'Ends With';
        } else {
            $wordOptionT = 0;
        }

        if($alert_type == 1 || $alert_type == 2) {
            $html = '<tr id="'.  $last_id .'">
                <td>'. $word .'</td><td>'. $wordTypeT .'</td><td>'. $wordOptionT .'</td>
                <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $last_id .')>Delete</button></td></tr>';
        } else if($alert_type == 3 || $alert_type == 4) {
            $html = '<tr id="'.  $last_id .'">
                <td>'. $word .'</td><td>'. $wordOptionT .'</td>
                <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $last_id .')>Delete</button></td></tr>';
        } else { //5
            $html = '<tr id="'.  $last_id .'">
                <td>'. $word .'</td>
                <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $last_id .')>Delete</button></td></tr>';
        }

        $dataArr = array("status" => 200, "msg" => "Added Successfully", "result" => $html);
        echo json_encode($dataArr);
    }
}


if(isset($_GET['fun']) && $_GET['fun'] == 'deleteWordFun') {
    $id = $_POST['id'];
    $conn->query("DELETE FROM tbl_create_alert WHERE id = $id");
    echo '1';
}

?>
