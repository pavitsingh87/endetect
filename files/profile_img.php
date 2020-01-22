<?php
if(!isset($_GET['eid']) || $_GET['eid'] == "") {
    echo "Some Error Ocuur";
    header("Location: manageusers.php");
}
?>

<html>
<head>
    <title>Profile Image</title>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/croppie/croppie.js"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="js/croppie/croppie.css">
</head>
    <body>
        <div class="container">
            <br><br>
			<div class="panel panel-default">
  				<div class="panel-heading">Select Profile Image</div>
  				<div class="panel-body" align="center">
  					<input type="file" name="upload_image" id="upload_image" />
  					<br />
  					<div id="uploaded_image"></div>
  				</div>
  			</div>
  		</div>
    </body>
</html>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Upload & Crop Image</h4>
      		</div>
      		<div class="modal-body">
        		<div class="row">
  					<div class="col-md-6 text-center">
                        <div id="image_demo"></div>
  					</div>
  					<div class="col-md-3">
                        <button class="btn btn-success crop_image">Crop & Save Image</button>
					</div>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
    </div>
</div>

<script>
$(document).ready(function() {
	$('#upload_image').on('change', function() {
		var reader = new FileReader();
		reader.onload = function(event) {
			$image_crop.croppie('bind', {
				url: event.target.result
			}).then(function() {
				console.log('jQuery bind complete');
			});
		}
		reader.readAsDataURL(this.files[0]);
		$('#uploadimageModal').modal('show');
	});

	$image_crop = $('#image_demo').croppie({
		enableExif: true,
		viewport: {
			width: 100,
			height: 100,
			type: 'square' //circle
		},
		boundary: {
			width: 200,
			height: 200
		}
	});

	$('.crop_image').click(function(event) {
		$image_crop.croppie('result', {
			type: 'canvas',
			size: 'viewport'
		}).then(function(response) {
			$.ajax({
				url: "profile_img_submit.php",
				type: "POST",
				data: {
					"image": response,
                    "eid": "<?php echo $_GET['eid']; ?>"
				},
				success: function(data) {
					$('#uploadimageModal').modal('hide');
					$('#uploaded_image').html(data);

                    window.location.href = "manageusers.php";
				}
			});
		})
	});

});
</script>
