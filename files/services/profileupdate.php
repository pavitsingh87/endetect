<?php
$idbar = $_REQUEST['profileeditid'];
?>
<div class="clearfix"></div>
<div>
	<div class="form-horizontal"
		style='background-color: #Eff3ef; padding: 10px;'>
		<div style="float: left; width: 400px;">
			
			<div class="form-group">
				<div id="upload-wrapper">
					<div align="center">
						<form action="processupload.php" onSubmit="return false"
							method="post" enctype="multipart/form-data" id="MyUploadForm">
							<input name="image_file" id="imageInput" type="file" /> <input
								type="submit" id="submit-btn" value="Upload" /> <img
								src="images/ajax-loader.gif" id="loading-img"
								style="display: none;" alt="Please Wait" />
						</form>
						<div id="statustxt">0%</div>
						<div id="output"></div>
					</div>
				</div>

			</div>
			
		</div>
	</div>
</div>


<div class="clearfix"></div>