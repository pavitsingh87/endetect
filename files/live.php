<?php
include("commonfunctions.php");
$title="Live Activity | EnDetect";
include("header.php");?>
<body onload="lastrecordid()" ng-app="submitExample" id="pavdescription1">	
<?php include("headerbar.php");?>
 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
 <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript">
function loadnew(str)
{
	document.getElementById('loadinggif').style.display="block";
	var incrementer = document.getElementById('streamuserres').value;
	var addition = parseInt(incrementer)+1;
	var searchInput = document.getElementById('searchInput').value;
	var strnglen = searchInput.length;
	
	document.getElementById('streamuserres').value=addition;
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          	document.getElementById('loadinggif').style.display="none";
          	document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
            
           var recidno = document.getElementById("lastrecid").value;
           if(str==1)
           {
            $("#endetect"+recidno).effect("pulsate");
            $("#endetect"+recidno).animate({
                backgroundColor: '#E9EAED'
            }, 1000);
           }
            if(strnglen<1)
            {    
           		 setTimeout(function(){ newdatacome(); }, 3000);
            }
        }
    }
    xmlhttp.open("POST","services/homestream.php",true);
    xmlhttp.send();
}

function newdatacome()
{
	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			
        	var checkval = xmlhttp.responseText;
        	var lastrecid =  document.getElementById("lastrecid").value;

        	if(lastrecid != checkval)
        	{        	
	            document.getElementById("lastrecid").value =xmlhttp.responseText;
	            loadnew("1");
        	}
        	else
        	{
        		homestreamdata();
        	}
        }
    }
    xmlhttp.open("POST","services/getlastrecid.php",true);
    xmlhttp.send();
}

function lastrecordid()
{
	document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        	          	
            document.getElementById("lastrecid").value =xmlhttp.responseText;
            homestreamdata();
        }
    }
    xmlhttp.open("POST","services/getlastrecid.php",true);
    xmlhttp.send();
}

function homestreamdata()
{
		
		document.getElementById('loadinggif').style.display="block";
		var searchInput = document.getElementById('searchInput').value;
		var strnglen 	= searchInput.length;
		
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            	document.getElementById('loadinggif').style.display="none";            	
                document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
                if(strnglen<1)
                {  
                setTimeout(function(){ newdatacome(); }, 3000);
                }
            }
        }
        xmlhttp.open("POST","services/homestream.php",true);
        xmlhttp.send();
    }

function loadmore(id)
{
	id.style.display="none";
	document.getElementById('loadinggif').style.display="block";
	var incrementer = document.getElementById('streamuserres').value;
	var addition = parseInt(incrementer)+1;
	document.getElementById('streamuserres').value=addition;
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          	document.getElementById('loadinggif').style.display="none";
          	
            document.getElementById("streamuser").innerHTML = document.getElementById("streamuser").innerHTML + xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/homestream.php?streamolder=1&lastrecordid="+addition,true);
    xmlhttp.send();
}
    </script>
<!-- header ends here -->

<!-- content starts here -->
<div id="content-main" ng-controller="ExampleController">
	<!-- display global alert messages -->
	<!-- sidebar template -->
	<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
	<style type="text/css">
		#profile-picture {
			position: relative;
			margin-left: 1px;
		}
		
		#profile-picture-caption {
			line-height: 26px;
			margin-bottom: -1px;
			margin-left: 4px;
			width: 180px;
		}
	</style>
	<div id="pen1">
		<div id="profile-picture"></div>
		<br class="clearfix">
		<?php homemenuoptions();
            //useroptions();
            categories(); ?>
		<br> <br>
	</div>
	<!-- main template -->
	<div id="pen2">
		<div id="pen2-left">
            <div class="col-md-12">
                <div class="col-md-6">
                <h3>Live</h3>
                </div>
                <div class="col-md-4 text-right">
                    <a href="/">( Exit Live )</a>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr> 
			<div class="message-container">
				<div class="message-form-content">
				<input type="hidden" id="lastrecid" name="lastrecid" value="">
 				<input type="hidden" id="streamuserres" name="streamuserres" value="0">
					<div id="streamuser">
						
					</div>
					<center>
						<div class="loadinggif" id="loadinggif">
							<img src="images/loading.gif">
						</div>
					</center>
					
					<div class="alert alert-dismissable alert-danger fade in"
						id="ascrec" style="display: none;">No more records found</div>
				</div>
			</div>
			<div id="load-content"></div>
			<div id="messages">
				<div style="margin: 10px; float: right;"></div>
				<br clear="all">
				<div id="resultval"></div>
			</div>
			<center></center>
		</div>
		<br class="clearfix">
	</div>

	<!-- pagination -->
</div>

<!-- footer starts here 
	<div id="footerContainer"></div>-->
<!-- footer ends here -->
</div>


<?php include("footer.php")?>