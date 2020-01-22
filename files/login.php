<?php
include_once("connection.php");

if(isset($_SESSION["email"])) {
    header("Location: ". baseurl);
}
?>
<!doctype html>
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Endetect - Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/proton.css">
    <link rel="stylesheet" href="css/animate.css">
    <style>
    .login-page .wrapper .panel-heading > div img{position:relative;display:block;float:left;top:5px;width:auto!important;height:44px;margin:0;margin-right:0;margin-right:5px}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
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

        <form method="POST" action="services/login.php">
            <section class="wrapper scrollable animated fadeInDown">
                <section class="panel panel-default">
                    <div class="panel-heading">
                        <div>
                            <img src="images/en-detect.png" alt="Endetect" >
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div style="width:90%;">
                            <?php if (isset($_REQUEST['error']) && $_REQUEST['error']=='1') { ?>
                            <div class="alert alert-dismissable alert-danger fade in" id="alertin"><?php echo "Username & Password incorrect"; ?></div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control input-lg" id="email" placeholder="Email" name='email' value="">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control input-lg" id="password" placeholder="Password" name='password' value="">
                            </div>
                            </div>
                        </li>
                    </ul>
                    <div class="panel-footer">
                        <input type='submit' class="btn btn-lg btn-success" value="LOGIN TO YOUR ACCOUNT">
                        <br>
                        <!-- <a class="forgot" href="forgetpassword.php" style="font-family:arial;">Forgot Your Password?</a> -->
                    </div>
                </section>
            </section>
        </form>
    </body>
</html>
