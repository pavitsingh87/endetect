<?php 
@session_start();
include("connection.php");
$v = base64_decode($_REQUEST["v"]);
$varr = explode("_",$v);
$email = $varr["0"];
$timestamp = $varr["1"];
$gh = time()-$timestamp;
$ownerRequest = $conn->query("select * from U_ownerrequest where email='".$email."' AND requestedtime='".$timestamp."' AND requesttype='1' AND status='0'");
if($ownerRequest->num_rows>0)
{

}
else
{
    $gh=60000;
}
if($_POST["flagCheck"]=="1")
{
  // change password associated with email id
  $conn->query("update U_endowners set password='".md5($_POST["password"])."' where email='".$email."'");
  $conn->query("update U_ownerrequest set status='1' where email='".$email."' AND requestedtime='".$timestamp."' AND requesttype='1'");
  ?>
  <script>
    window.location.href=baseurl."login";
  </script>
  <?php 
}
?>
<!doctype html>
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="iso-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Reset Password - EnDetect</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <!-- Proton CSS: -->
        <link rel="stylesheet" href="css/proton.css">
        <link rel="stylesheet" href="css/animate.css">
         <style>
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
        </style>
        
        <!-- Common Scripts: -->
        <script>
        (function () {
          var js;
          if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
            js = 'https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
          } else {
            js = 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
          }
          document.write('<script src="' + js + '"><\/script>');
        }());
        </script>
        <script src="scripts/vendor/modernizr.js"></script>
        <script src="scripts/vendor/jquery.cookie.js"></script>
        <script src="js/angular.min.js"></script>
        <script type="text/javascript">
          var app = angular.module('resetPassword', []);
          app.controller('resetPasswordCtrl', function ($scope, $http, $timeout) {
              $scope.passChangeRest = "0";
              $scope.gh = function(gh)
              {
                  if(gh>6000)
                  {
                    $("#errorMsg").html("<div class='alert alert-warning' style='width:87%; font-family:arial;'>Your temporary code has been used or it is expired!<br> <a href='http://192.168.1.4/forgetpassword'>Click to Retry</a></div>");
                    $scope.passChangeRest = "1";
                  }
              }
              $scope.resetpassword = function()
              {
                  if($scope.password==$scope.confirm)
                  {
                    document.getElementById("resetpasswordform").method = "POST";
                    
                    $timeout( function(){
                        $("#resetpasswordform").submit();
                      }, 1000);
                  }
                  else
                  {
                     $("#errorMsg").html("<div class='alert alert-warning' style='width:87%;font-family:arial;'>Password does not match.</div>");
                    $timeout( function(){
                        $("#errorMsg").html("");
                      }, 5000 );
                  }
              };
          });
        </script>
        <script type="text/javascript">
		    window.setTimeout(function() {
		    	$("#alertin").fadeOut(5000);
			 });
		</script>
    </head>

    <body class="login-page" ng-app="resetPassword" ng-controller="resetPasswordCtrl">
        
        <script>
	        var theme = $.cookie('protonTheme') || 'default';
	        $('body').removeClass (function (index, css) {
	            return (css.match (/\btheme-\S+/g) || []).join(' ');
	        });
	        if (theme !== 'default') $('body').addClass(theme);
        </script>
        <form id="resetpasswordform" name="resetpasswordform" ng-submit="resetpassword()">
            <section class="wrapper scrollable animated fadeInDown">
                <section class="panel panel-default">
                    <div class="panel-heading">
                        <div>
                            <img src="images/en-detect.png" alt="Endetect" >
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div id="errorMsg"></div>
                            <input type="hidden" name="flagCheck" id="flagCheck" value="1" />
                            <input type="hidden" name="timeDif" id="timeDif" ng-model="timeDif" ng-init="gh('<?php echo $gh; ?>')">
                            <?php if ($_REQUEST['error']=='1') { ?>
                            <div class="alert alert-dismissable alert-danger fade in" id="alertin"><?php echo "Username & Password incorrect"; ?></div>
                            <?php } ?>
                            <div class="form-group" ng-hide="passChangeRest=='1'">
                                <input type="password" name="password" placeholder="New Password" class="form-control input-lg" ng-model="password" required="" minlength="8" maxlength="100" autocomplete="off" style="">
                            </div>
                            <div class="form-group" ng-hide="passChangeRest=='1'">
                                <input type="password" class="form-control input-lg" name="confirm" placeholder="Confirm Password" ng-model="confirm" maxlength="100" required="">
                            </div>
                        </li>
                    </ul>
                    <div class="panel-footer">
                        <input type='submit'  ng-hide="passChangeRest=='1'" class="btn btn-lg btn-success" value="Reset">
                        <br>
                        <a class="forgot" href="forgetpassword.php" style="font-family:arial;">Forgot Your Password?</a>
                    </div>
                </section>
            </section>  
        </form>
    </body>
</html>
