<?php
include_once("connection.php");
$v = base64_decode($_REQUEST["v"]);
$varr = explode("_",$v);
$email = $varr["0"];
$timestamp = $varr["1"];

$gh = time()-$timestamp;
$ownerRequest = $conn->query("select * from U_ownerrequest where email='".$email."' AND requestedtime='".$timestamp."' AND requesttype='2' AND status='0'");
if($ownerRequest->num_rows>0)
{

}
else
{
    $gh=60000;
}
if($gh>6000)
{
    $urlredirect = baseurl;
    $errormsg = '<div style="color:red;font-family:arial;">Verification code expired</div>';
}
else
{

    if($email!="")
    {
    	$update_endowner = $conn->query("update U_endowners set active=1, noauth=1 where email='".$email."'");
        $conn->query("update U_ownerrequest set status='1' where email='".$email."' AND requestedtime='".$timestamp."' AND requesttype='2'");
    	$select_query = $conn->query("select * from U_endowners where email='".$email."'");
    	$selnum = $select_query->num_rows;
    	if($selnum > 0)
    	{
            $login_array = $select_query->fetch_assoc();
    		$_SESSION['email']		= $login_array['email'];
    		$_SESSION['name']		= $login_array['name'];
    		$_SESSION['ownerid']	= $login_array['sno'];
    		$_SESSION['company']	= $login_array['company'];
    		$urlredirect = baseurl;
    		$errormsg = '<div style="color:green;font-family:arial;">Your account is successfully verified</div>';
    	}
    }

    else
    {
    	$urlredirect = baseurl ."login.php";
    	?>
    	<script type="text/javascript">
    		window.location.href = "<?php echo baseurl; ?>login.php";
    	</script>
    	<?php
    }
}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 lt-ie10"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 lt-ie10"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="iso-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Endetect - Login</title>
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
            js = 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
          } else {
            js = 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
          }
          document.write('<script src="' + js + '"><\/script>');
        }());
        </script>
        <script src="scripts/vendor/modernizr.js"></script>
        <script src="scripts/vendor/jquery.cookie.js"></script>
        <script type="text/javascript">
		    window.setTimeout(function() {
		    	$("#alertin").fadeOut(5000);
			 });
		</script>
    </head>

    <body class="login-page">

        <script>
	        var theme = $.cookie('protonTheme') || 'default';
	        $('body').removeClass (function (index, css) {
	            return (css.match (/\btheme-\S+/g) || []).join(' ');
	        });
	        if (theme !== 'default') $('body').addClass(theme);
        </script>
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <form method="POST" action="services/login.php">
            <section class="wrapper scrollable animated fadeInDown">
                <section class="panel panel-default">
                    <div class="panel-heading">
                        <div>
                            <img src="<?php echo baseurl; ?>images/en-detect.png" alt="Endetect" >
                        </div>
                        <p>
                        	<?php echo $errormsg; ?>
                        	<br><br><br>
                        	<a href="<?php echo baseurl; ?>" style="font-family:arial;" class="btn btn-success">Click to login</a>
                        </p>
                    </div>
                </section>
            </section>
        </form>
    </body>
</html>
