<?php @session_start(); ?>
<!doctype html>
<html class="no-js">
<head>
<meta charset="iso-8859-1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>Endetect - Login</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/proton.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/font-awesome.css" type="text/css" />
<link rel="stylesheet" href="css/font-titillium.css" type="text/css" />
<link rel="stylesheet" href="css/switchery.css" type="text/css" />
<script>
        (function () {
          var js;
          if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
            js = 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
          } else {
            js = 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
          }
          document.write('<script src="' + js + '"><\/script>');
        }());
        </script>
<script src="js/modernizr.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-1.8.2.custom.min.js"></script> 
<script src="js/emailalreadyexist.js"></script>
<script src="js/mainCtrl.js"></script>
<link href="css/css.css" rel="stylesheet" type="text/css" />
<style>
.list-group-item
{
	width:90% !important;
}
	.input-group-addon
	{
		padding: 4px 12px !important;
	}
	.danger {
		color: rgb(255, 255, 255);
background-color: rgb(217, 83, 79);
border-color: rgb(212, 63, 58);
	}
	.success
	{
		color: rgb(255, 255, 255);
background-color: rgb(92, 184, 92);
border-color: rgb(76, 174, 76);
    border-left-color: rgb(76, 174, 76);
	}
	.login-page .wrapper .form-group input {
    margin: 0 auto;
}
	.login-page .wrapper .panel-heading > div img
	{
		position: relative;
		display: block;
		float: left;
		top: 5px;
		width: auto !important;
		height: 44px;
		margin: 0;
		margin-right: 0px;
		margin-right: 5px;
	}
	.login-page .wrapper .form-group input {
    width: 100% !important;
    margin: 0 auto;
}
</style>
</head>

<body class="login-page">

	<script>
	        var theme = $.cookie('protonTheme') || 'default';
	        $('body').removeClass (function (index, css) {
	            return (css.match (/\btheme-\S+/g) || []).join(' ');
	        });
	        if (theme !== 'default') $('body').addClass(theme);
        </script>
	<form action="services/signup.php" data-parsley-namespace="data-parsley-"
		data-parsley-validate autocomplete="off" method="POST">
		<section class="wrapper scrollable animated fadeInDown">
			<section class="panel panel-default">
				<div class="panel-heading">
					<div>
						<img src="images/en-detect.png" width="auto">
					</div>
				</div>
				<div class="panel panel-default panel-block">
					<div class="list-group">
						<div class="list-group-item">
							<h4 class="section-title">Register</h4>
							<div>
								<?php
								if (isset($_SESSION['signup'])) {  
								if($_SESSION['signup']=="1") 
								{ 
									echo "<div style='color:green;'>Successfully Registered. <br><p>Please confirm your email address by clicking on the link in the verification email delivered to your inbox</p></div>";		
									@$_SESSION['signup']="0";
									$_POST=false;
								} } ?>
							</div>
							<div class="form-group">
								<label>Name <span class="text-danger">*</span></label> <input
									type="text" name="name" class="form-control" placeholder="Name"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>Company Name <span class="text-danger">*</span></label> <input
									type="text" name="company_name" class="form-control" placeholder="Company Name"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>Email <span class="text-danger">*</span></label> 
								<div class="input-group" data-validate="email">
									<input style="display:none" type="text" class="form-control" name="email" id="email" placeholder="Email" >
									<input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email" required>
									<span class="input-group-addon" id="ght">&nbsp;</span>
								</div>
								<div id="availability_status"></div>
							</div>
							<div class="form-group">
								<label>Password <span class="text-danger">*</span></label> <input
									id="password"  name="password"  type="password" class="form-control"
									placeholder="Enter Password" style="display:none">
									<input type="password" name="user_password" id="user_password" class="form-control" placeholder="Password" required>
							</div>
														<div class="form-group">
								<label>Address 1 <span class="text-danger">*</span></label> <input
									type="text" name="address1" class="form-control" placeholder="Address 1"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>Address2 <span class="text-danger">*</span></label> <input
									type="text" name="address2" class="form-control" placeholder="Address 2"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>Country <span class="text-danger">*</span></label> 
								<input type="text" name="country" class="form-control" id="dd_user_input" onkeyup="keyupre()"  placeholder="Country"
									data-parsley-required="true"/>
									<input type="hidden" id="countrycode" name="countrycode">
							</div>
							<div class="form-group">
								<label>State <span class="text-danger">*</span></label> <input
									type="text" name="state" class="form-control" placeholder="State"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>City <span class="text-danger">*</span></label> 
								<input
									type="text" name="city" class="form-control"  id="dd_user_input1" onkeyup="keyupre1()"  placeholder="City"
									data-parsley-required="true">
							</div>
							<div class="form-group">
								<label>Phone <span class="text-danger">*</span></label> <input
									type="text"  name="phone"  class="form-control" placeholder="Phone"
									data-parsley-type="phone" data-parsley-required="true">
							</div>	
							<div class="form-group">								
								<div style="margin-left:50px;">
								<span style="float:left;margin-right:10px;" class="btn btn-warning" id="free">
								Free / Trial 
								</span>
								<span class="toggle-bg">
									<input type="radio" name="toggle" value="off"  onclick="toggleswitch()">
									<input type="radio" name="toggle" value="on"  onclick="toggleswitch()">
									<span class="switch" id="switch"></span>
								</span>
								<span  style="float:left;margin-left:10px;" class="btn btn-info"  id="licensed">
									Licensed
								</span>
								</div>
							</div>	
							<br style="clear:both">
							<div style="margin-top:10px;margin-left:50px;display:none;" id="licenseincrement">
								
								   <style type="text/css">
									   div.incrementer {
									        width: 100px;
									        float:left;
									    }
									    a.plus
									    {
									    	color: #3699D2;
											text-decoration: none;
									      	font-size:24px;							       
									        float:left;
									        margin-left:5px;
									        position:relative;
									        top:-3px;
									    }
									    a.minus {
									    	color: #3699D2;
											text-decoration: none;
									      	font-size:24px;							       
									        float:left;
									        margin-left:5px;
									        position:relative;
									        top:10px;
									        left:-13px;
									    }
								   </style>
								    <script src="https://thaibault.github.io/jQuery-incrementer/distributionBundle/jquery-2.1.1.js" type="text/javascript"></script>
								    <script src="https://thaibault.github.io/jQuery-incrementer/distributionBundle/jquery-tools-1.0.js" type="text/javascript"></script>
								    <script src="https://thaibault.github.io/jQuery-incrementer/distributionBundle/jquery-incrementer-1.0.js" type="text/javascript"></script>
								    <script type="text/javascript">
									    $(function($) {
									        $('#increment').Incrementer({
									            domNodeSelectorPrefix: 'body form div.{1}',
									            onInvalidNumber: $.noop(),
									            onTypeInvalidLetter: $.noop(),
									            logging: false,
									            step: 1,
									            min: 1,
									            max: 9999,
									            domNode: {
									                plus: '> a.plus',
									                minus: '> a.minus'
									            },
									            neededMarkup: '<a href="#" class="plus" style="float:left;width:10px;display:block;">+</a>' +
									                          '<a href="#" class="minus" style="float:left;width:10px;display:block;">-</a>',
									            logging: false
									        });
									    });								
								    </script>								    
								    <div class="btn btn-warning" style="float:left;margin-right:20px;">No. of License's</div><input class="" type="text" value="1" id="increment" name="licensenum" style="width:50px;height:28px;float:left;display:block;"></input>								      
									<br style="clear:both">	
							</div>	
						</div>						
					</div>					
					<footer class="panel-footer text-right">
						<input type='submit' class="btn btn-lg btn-success" value="SignUp">
					</footer>
				</div>
			</section>
		</section>
	</form>
	<p>
		<br>
	</p>
	<script>
	function toggleswitch()
	{
		var q=$("input[name='toggle']:checked").val();
		
		if(q=='on')
		{
			$('#licensed').toggleClass('btn-info btn-warning');
			$('#free').toggleClass('btn-warning btn-info');
			document.getElementById('licenseincrement').style.display='block';
		}
		else
		{
			$('#licensed').toggleClass('btn-info btn-warning');
			$('#free').toggleClass('btn-warning btn-info');
			document.getElementById('licenseincrement').style.display='none';
		}
	}
	</script>
	<script src="scripts/bootstrap.min.js"></script>

	<!-- Proton base scripts: -->

	<script src="scripts/main.js"></script>
	<script src="scripts/proton/common.js"></script>
	<script src="scripts/proton/main-nav.js"></script>
	<script src="scripts/proton/user-nav.js"></script>



	<!-- Page-specific scripts: -->
	<script src="scripts/proton/sidebar.js"></script>
	<script src="scripts/proton/forms.js"></script>
	<!-- jsTree -->
	<script src="scripts/vendor/jquery.jstree.js"></script>

	<!-- Parsley.js forms validation -->
	<!-- http://parsleyjs.org/ -->
	<script src="scripts/vendor/parsley.min.js"></script>
	<script src="scripts/vendor/parsley.extend.min.js"></script>
</body>
</html>