<?php 
@session_start();
error_reporting(E_ERROR|E_WARNING);
require 'vendor2/autoload.php';
use Mailgun\Mailgun;
include("connection.php");
$mgClient = new Mailgun('key-2aa8dd64253c76323a1e5ff7b2c4c9b0');
$domain = "mg.endetect.com";
if($_REQUEST["resetpass"]=="1")
{
    $checkemail = $conn->query("select * from U_endowners where email='".$_REQUEST["email"]."'");
    $checkemailnum = $checkemail->num_rows;
    if($checkemailnum>0)
    {
        $currenttimestamp = time(); 
        $conn->query("insert into U_ownerrequest set email='".$_REQUEST["email"]."', requesttype='1', requestedtime='".$currenttimestamp."', status=0");
        $enc = "https://app.endetect.com/resetpassword?v=".base64_encode($_REQUEST["email"]."_".$currenttimestamp);
        ob_start();
        ?>
            <div style="width:100%!important;margin:0;padding:0;border:0;background-color:#f2f2f2">
              <table style="min-width:320px;border-collapse:collapse;border-spacing:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2">
                <tbody><tr>
                  <td style="padding:25px 10px">
                    <table style="border-collapse:collapse;border-spacing:0;font-family:Helvetica Neue,Helvetica,Arial,sans-serif" width="580" cellspacing="0" cellpadding="0" border="0" align="center">
                      
                      <tbody><tr>
                        <td style="padding:0 0 25px 0"><a href=""><img src="https://app.endetect.com/images/endetectlogo.png"></a></td>
                      </tr>
                      
                      <tr>
                        <td style="border-radius:8px 8px 0 0" bgcolor="#2c343c"><img src="https://app.endetect.com/images/herounit.jpg" style="width:100%;max-width:580px;height:auto;border-radius:8px 8px 0 0;vertical-align:top" class="CToWUd a6T" tabindex="0" width="580"></td>
                      </tr>
                      
                      <tr>
                        <td style="padding:50px 50px 0 50px" bgcolor="#ffffff">
                          <h1 style="margin:0 0 33px 0;color:#2c343c;font-size:40px;line-height:40px">EnDetect Password Reset,</h1>
                          <p style="margin:0 0 10px 0;color:#2c343c;font-size:24px;line-height:40px">Please follow the link below to reset your EnDetect password</p>
                          <p style="margin:20 0 10px 0;color:#2c343c;font-size:24px;line-height:40px">
                            <a href="<?php echo $enc; ?>" style="display:block;width:230px;height:60px;margin:0;background-color:#48b0f7;border-radius:4px;color:#fff;font-size:18px;line-height:60px;text-align:center;text-decoration:none!important" target="_blank" data-saferedirecturl="https://www.google.com/url?q=<?php echo $enc;?>&amp;source=gmail&amp;ust=<?php echo time(); ?>"><strong style="font-weight:400;text-decoration:none!important">Reset your password</strong></a>
                          </p>
                        </td>
                      </tr>
                      <tr>
                        <td style="margin:30px 0 0">
                          <table style="border-collapse:collapse;border-spacing:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
                            <tbody>
                              <tr>
                                <td style="padding:20px 50px 0 50px;line-height:30px" width="50%" valign="middle"><strong style="font-weight:400px;text-decoration:none!important">
                                    <u></u>If you have not requested your password to be reset, please ignore this message. This link is valid for 30 minutes<br><u></u></strong></td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td style="border-radius:0 0 8px 8px" bgcolor="#ffffff">
                          <table style="border-collapse:collapse;border-spacing:0" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <td style="padding:15px 0 25px 50px" width="50%" valign="middle">
                                  <a href="#m_2698880151752886151_" style="margin-right:25px;color:#2c343c;font-size:16px;line-height:30px;text-decoration:none!important"><strong style="font-weight:400;text-decoration:none!important">
                                      <u></u>Thanks,<u></u></strong></a><a href="#m_2698880151752886151_" style="color:#2c343c;font-size:16px;line-height:30px;text-decoration:none!important"><strong style="font-weight:400;text-decoration:none!important"><br>
                                      <u></u>The EnDetect Team<u></u></strong></a>
                                </td>
                                <td style="padding:15px 50px 25px 0" width="50%" valign="bottom" align="right">
                                  <a href="mailto:info@EnDetect.com" style="margin-left:25px;color:#48b0f7;font-size:16px;line-height:30px;text-decoration:none!important" target="_blank"><strong style="font-weight:400;text-decoration:none!important">
                                      <u></u>info@endetect.com<u></u></strong></a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody></table>
                  </td>
                </tr>
              </tbody></table>
            </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        $result = $mgClient->sendMessage($domain, array(
          'from'  => 'Endetect <info@endetect.com>',
          'to'  => $_REQUEST["email"],
          'subject' => 'EnDetect Password Reset Requested',
          'html'  => $output
        ));
        $_REQUEST=false;
        $resetpassword="success";
        ?>
        <?php
    }
    else
    {
        $_REQUEST=false;
        $resetpassword="error";
    }
}
?>
<!doctype html>
<html class="no-js"> <!--<![endif]-->
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
            js = 'https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
          } else {
            js = 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
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
        <form method="POST" action="">
            <section class="wrapper scrollable animated fadeInDown">
                <section class="panel panel-default">
                    <div class="panel-heading">
                        <div>
                            <img src="https://app.endetect.com/images/en-detect.png" alt="Endetect" >
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="login-text" style="font-family:arial;">
                                Forget your password? 
                            </span>
                            <?php if ($_REQUEST['error']=='1') { ?>
                            <div class="alert alert-dismissable alert-danger fade in" id="alertin"><?php echo "Username & Password incorrect"; ?></div>
                            <?php } ?>

                            <?php if($resetpassword=="success" || $resetpassword=="error") { ?>
                            <p style="width:85%;font-family:arial;color: #ff5c00 !important;font-size: 12px;">If you have an EnDetect account, you will soon receive a password reset email. Follow the link in the email to reset your password.</p>
                            <?php } ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="hidden" name="resetpass" id="resetpass" value="1">
                                <input type="email" class="form-control input-lg" id="email" placeholder="Email" name='email' value="">
                            </div>
                            
                        </li>
                    </ul>
                    <div class="panel-footer">
                        <input type='submit' class="btn btn-lg btn-success" value="Submit">
                        <br>
                        <a class="forgot" href="login.php" style="font-family:arial;">Login to your account</a>
                    </div>
                </section>
            </section>
        </form>
    </body>
</html>
