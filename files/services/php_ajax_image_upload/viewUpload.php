<html>
<head>
<title>PHP AJAX Image Upload</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript">

function eventpass()
{
	
		var form = document.getElementById('uploadForm');
		var formData = new FormData(form);
		document.getElementById("loadingefect").style.display="block";
		$.ajax({
        	url: "upload.php",
			type: "POST",
			data:  formData,
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
		    {
				document.getElementById("loadingefect").style.display="none";
				$("#imgfile").val(data);
				$("#targetLayer").html(data);
		    },
		  	error: function() 
	    	{
	    	} 	        
	   });
	
}
</script>
</head>
<body>

<form id="uploadForm" action="upload.php" method="post">
<table>
<tr>
<td>
<div id="targetLayer">No Image</div>
</td>
</tr>
<tr>
<td>
	<input type="hidden" name="imgfile" id="imgfile" value="0">
	<input name="userImage" type="file" onchange="eventpass()" style="width:200px;"/>
	<div id="loadingefect" style="display:none;"><img src="../../images/loader.gif"></div>
</td>
</tr>

</table>




</form>
</body>
</html>