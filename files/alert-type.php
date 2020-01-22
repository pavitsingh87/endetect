<?php
$title="Alert Type | EnDetect";
include_once("commonfunctions.php");
checkOwnerSession();
include_once("header.php");
?>
<body ng-app="submitExample">
<?php
include_once("headerbar.php");

//First
$resultData = $conn->query("SELECT * FROM tbl_create_alert2 WHERE id = 1");
$resultRow = $resultData->fetch_object();
?>
<style>
textarea{max-width:initial}
fieldset{padding:0 10px 10px 10px;margin:0 0 20px;border:1px solid #000;min-width:auto}
legend{width:auto;margin-bottom:10px;font-size:17px;font-weight:700}
input[type="radio"],input[type="checkbox"]{margin:1px 0 0}
input[type="submit"]{color:#fff;background:red;width:200px;border:initial}
.radio-inline, .checkbox-inline{padding-bottom:10px;}
.scroll-div{height:220px;overflow-x:auto;}
.ssSmall{padding:1px 10px;font-size:12px;}
.tab-pane{padding-top:20px;}
</style>

<div id="content-main" ng-controller="ExampleController">
    <form action="alert-type-submit.php" method="post">
    	<div class="row" style="margin-left:0px;">
            <div class="col-md-11">
                <h2>Create Alert</h2>
                <br>

                <div class="tab-container">
                    <ul class="nav nav-tabs">
                        <li class="nav-item active"><a href="#T001" data-toggle="tab" role="tab">General</a></li>
                        <li class="nav-item"><a href="#T002" data-toggle="tab" role="tab">Word</a></li>
                        <li class="nav-item"><a href="#T003" data-toggle="tab" role="tab">File</a></li>
                        <li class="nav-item"><a href="#T004" data-toggle="tab" role="tab">Application</a></li>
                        <li class="nav-item"><a href="#T005" data-toggle="tab" role="tab">URL</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="T001" role="tabpanel">
                            <div class="col-md-4">
                            	<div class="form-group">
                            		<label>Internet Usage Alert After Exceeding</label>
                            		<div class="input-group">
                            			<input type="text" class="form-control" name="internet_usage" value="<?php echo $resultRow->internet_usage; ?>">
                            			<div class="input-group-addon">MB</div>
                            		</div>
                            	</div>
                            </div>
                            <div class="col-md-3">
                            	<div class="form-group">
                            		<label>Idle Alert (Idle time in a day)</label>
                            		<select class="form-control" name="idle_time">
                            			<option value="0">--None--</option>
                                        <option value="1" <?php if($resultRow->idle_time == 1) {echo 'selected';} ?>>1 Time</option>
                            			<?php foreach (range(2, 20) as $val) { ?>
                            				<option value="<?php echo $val; ?>" <?php if($resultRow->idle_time == $val) {echo 'selected';} ?>><?php echo $val; ?> Times</option>
                            			<?php } ?>
                            		</select>
                            	</div>
                            </div>
                            <div class="col-md-3">
                            	<div class="form-group">
                            		<label>USB Drive Access Alert</label>
                            		<select class="form-control" name="usb_on">
                            			<option value="0" <?php if($resultRow->usb_on == '0') {echo 'selected';} ?>>No</option>
                            			<option value="1" <?php if($resultRow->usb_on == '1') {echo 'selected';} ?>>Yes</option>
                            		</select>
                            	</div>
                            </div>
                            <div class="col-md-3">
                            	<input type="submit" class="btn btn-danger" name="submit" value="UPDATE">
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-pane" id="T002" role="tabpanel">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Words Alert:</legend>
                                    <div class="form-group">
                                        <label>Enter Text*</label>
                                        <input type="text" class="form-control" id="word">
                                    </div>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="wordType" id="wordTyped" value="1"> Typed Word
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="wordType" id="wordCopied" value="2"> Copied Word
                                    </label>
                                    <div class="form-group">
                                        <label>Select Option</label>
                                        <select class="form-control" id="wordOption">
                                            <option value="1">Exact Match</option>
                                            <option value="2">Containing</option>
                                            <option value="3">Begins With</option>
                                            <option value="4">Ends With</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-danger" id="wordBtn">Add</button>
                                    <span class="wordMsg"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 scroll-div">
                                <table class="table table-bordered" id="wordOutput">
                                    <tr><th>Word</th><th>Type</th><th>Option</th><th>Action</th></tr>
                                    <?php
                                    $resultData = $conn->query("SELECT * FROM tbl_create_alert WHERE alert_type = 1 ORDER BY word ASC");
                                    while($row = $resultData->fetch_object()) {
                                        $wordType = $row->word_type == 1 ? 'Typed' : 'Copied';
                                        if($row->word_option == 1) {
                                            $wordOption = 'Exact Match';
                                        } else if($row->word_option == 2) {
                                            $wordOption = 'Containing';
                                        } else if($row->word_option == 3) {
                                            $wordOption = 'Begins With';
                                        } else {
                                            $wordOption = 'Ends With';
                                        }

                                        echo '<tr id="'.  $row->id .'">
                                            <td>'. $row->word .'</td><td>'. $wordType .'</td><td>'. $wordOption .'</td>
                                            <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $row->id .')>Delete</button></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-pane" id="T003" role="tabpanel">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>File Alert:</legend>
                                    <div class="form-group">
                                        <label>Enter File Name*</label>
                                        <input type="text" class="form-control" id="file_name">
                                    </div>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="fileType" id="fileCopied" value="2"> Copied File
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="fileType" id="fileDeleted" value="3"> Deleted File
                                    </label>
                                    <div class="form-group">
                                        <label>Select Option</label>
                                        <select class="form-control" id="fileOption">
                                            <option value="1">Exact Match</option>
                                            <option value="2">Containing</option>
                                            <option value="3">Begins With</option>
                                            <option value="4">Ends With</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-danger" id="fileBtn">Add</button>
                                    <span class="fileMsg"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 scroll-div">
                                <table class="table table-bordered" id="fileOutput">
                                    <tr><th>File</th><th>Type</th><th>Option</th><th>Action</th></tr>
                                    <?php
                                    $resultData = $conn->query("SELECT * FROM tbl_create_alert WHERE alert_type = 2 ORDER BY word ASC");
                                    while($row = $resultData->fetch_object()) {
                                        $wordType = $row->word_type == 1 ? 'Typed' : 'Copied';
                                        if($row->word_option == 1) {
                                            $wordOption = 'Exact Match';
                                        } else if($row->word_option == 2) {
                                            $wordOption = 'Containing';
                                        } else if($row->word_option == 3) {
                                            $wordOption = 'Begins With';
                                        } else {
                                            $wordOption = 'Ends With';
                                        }

                                        echo '<tr id="'.  $row->id .'">
                                            <td>'. $row->word .'</td><td>'. $wordType .'</td><td>'. $wordOption .'</td>
                                            <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $row->id .')>Delete</button></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-pane" id="T004" role="tabpanel">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Application Title Alert:</legend>
                                    <div class="form-group">
                                        <label>Enter Application Title*</label>
                                        <input type="text" class="form-control" id="app_title">
                                    </div>
                                    <div class="form-group">
                                        <label>Select Option</label>
                                        <select class="form-control" id="appTitleOption">
                                            <option value="1">Exact Match</option>
                                            <option value="2">Containing</option>
                                            <option value="3">Begins With</option>
                                            <option value="4">Ends With</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-danger" id="appTitleBtn">Add</button>
                                    <span class="appTitleMsg"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 scroll-div">
                                <table class="table table-bordered" id="appTitleOutput">
                                    <tr><th>App Title</th><th>Option</th><th>Action</th></tr>
                                    <?php
                                    $resultData = $conn->query("SELECT * FROM tbl_create_alert WHERE alert_type = 3 ORDER BY word ASC");
                                    while($row = $resultData->fetch_object()) {
                                        if($row->word_option == 1) {
                                            $wordOption = 'Exact Match';
                                        } else if($row->word_option == 2) {
                                            $wordOption = 'Containing';
                                        } else if($row->word_option == 3) {
                                            $wordOption = 'Begins With';
                                        } else {
                                            $wordOption = 'Ends With';
                                        }

                                        echo '<tr id="'.  $row->id .'">
                                            <td>'. $row->word .'</td><td>'. $wordOption .'</td>
                                            <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $row->id .')>Delete</button></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="clearfix" style="margin-bottom:20px;"></div>

                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Application Running Alert:</legend>
                                    <div class="form-group">
                                        <label>Enter Application Running Name*</label>
                                        <input type="text" class="form-control" id="app_Run">
                                    </div>
                                    <div class="form-group">
                                        <label>Select Option</label>
                                        <select class="form-control" id="appRunOption">
                                            <option value="1">Exact Match</option>
                                            <option value="2">Containing</option>
                                            <option value="3">Begins With</option>
                                            <option value="4">Ends With</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-danger" id="appRunBtn">Add</button>
                                    <span class="appRunMsg"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 scroll-div">
                                <table class="table table-bordered" id="appRunOutput">
                                    <tr><th>App Running</th><th>Option</th><th>Action</th></tr>
                                    <?php
                                    $resultData = $conn->query("SELECT * FROM tbl_create_alert WHERE alert_type = 4 ORDER BY word ASC");
                                    while($row = $resultData->fetch_object()) {
                                        if($row->word_option == 1) {
                                            $wordOption = 'Exact Match';
                                        } else if($row->word_option == 2) {
                                            $wordOption = 'Containing';
                                        } else if($row->word_option == 3) {
                                            $wordOption = 'Begins With';
                                        } else {
                                            $wordOption = 'Ends With';
                                        }

                                        echo '<tr id="'.  $row->id .'">
                                            <td>'. $row->word .'</td><td>'. $wordOption .'</td>
                                            <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $row->id .')>Delete</button></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="tab-pane" id="T005" role="tabpanel">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>URL Alert:</legend>
                                    <div class="form-group">
                                        <label>Enter URL*</label>
                                        <input type="text" class="form-control" id="url_title">
                                    </div>
                                    <button type="button" class="btn btn-danger" id="urlBtn">Add</button>
                                    <span class="urlMsg"></span>
                                </fieldset>
                            </div>
                            <div class="col-md-5 scroll-div">
                                <table class="table table-bordered" id="urlOutput">
                                    <tr><th>URL</th><th>Action</th></tr>
                                    <?php
                                    $resultData = $conn->query("SELECT * FROM tbl_create_alert WHERE alert_type = 5 ORDER BY word ASC");
                                    while($row = $resultData->fetch_object()) {
                                        echo '<tr id="'.  $row->id .'">
                                            <td>'. $row->word .'</td>
                                            <td><button type="button" class="btn btn-danger ssSmall" onclick=deleteWordFun('. $row->id .')>Delete</button></td></tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div><!--tab-container closed-->

            </div><!--col-md-11 closed-->
    		<div class="clearfix"></div>
    	</div><!--row closed-->
    </form>
</div>

<script src="js/alert-type.js"></script>
<?php include_once("footer.php"); ?>
