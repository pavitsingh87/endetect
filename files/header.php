<?php
@session_set_cookie_params(0);
@session_start();

if((@$_SESSION['ownerid']=="") && (strlen(@$_SESSION['ownerid'])!='4'))
{
	?>
	<script type="text/javascript">
		window.location.href= "<?php echo baseurl; ?>login.php";
	</script>
	<?php
}
if(isset($_GET["eid"]) && $_GET["eid"] != "")
{
  $_REQUEST['enduserid'] = base64_decode($_GET["eid"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="-1">
<META NAME="author" CONTENT="Endetect">
<META NAME="description" CONTENT="Endetect">
<META NAME="keywords" CONTENT="">
<meta name="google-site-verification" content="" />
<link href="css/common.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>" media="all">
<link rel="stylesheet" type="text/css" href="css/fb-buttons.css?<?php echo time(); ?>" media="all">
<link href="css/news_feed.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="css/puloutpanel.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="css/LStyle.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="css/page.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="css/perfect-scrollbar.css?<?php echo time(); ?>" rel="stylesheet" />
<link href="css/datepicker.css?<?php echo time(); ?>" rel="stylesheet" />
<link href="css/tip-twitter.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/prettyPhoto.css?<?php echo time(); ?>" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
#TopMenu ul{position:relative;top:-9px}
#TopMenu .dropdown-menu ul li a{font-size:14px}
#TopMenu li a{font-size:18px}
#pen1 ul{margin:0;padding:0;list-style:none}
#pen1 .nav li{border-bottom:1px solid #eee}
#pen1 .nav li a{font-size:14px}
#pen1 .panel-body{padding:0}
#pen1 .panel-group .panel+.panel{margin-top:0;border-top:0}
#pen1 .panel-group .panel{border-radius:0}
#pen1 .panel-default>.panel-heading{color:#333;background-color:#fff;border-color:#e4e5e7;padding:0;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
#pen1 .panel-default>.panel-heading a{display:block;padding:10px 15px;text-decoration:none}
#pen1 .panel-default>.panel-heading a:after{content:"\e114";position:relative;top:5px;display:inline-block;font-family:'Glyphicons Halflings';font-style:normal;font-weight:400;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;float:right;transition:transform .25s linear;-webkit-transition:-webkit-transform .25s linear}
#pen1 .panel-heading a[aria-expanded="true"]:after{content:"\e114"}
</style>
<script src="js/jquery-2.1.1.js?<?php echo time(); ?>"></script>
<script src="js/bootstrap.min.js"></script>
<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="js/jquery.1.8.2.min.js"></script> -->
<script src="js/angular.min.js?<?php echo time(); ?>"></script>
<script src="js/ui-bootstrap-tpls-0.10.0.min.js?<?php echo time(); ?>"></script>
<script src="js/perfect-scrollbar.js?<?php echo time(); ?>"></script>
<script src="js/mainCtrl.js?<?php echo time(); ?>"></script>
<script src="js/css-pop.js?<?php echo time(); ?>"></script>
<script src="js/function.js?<?php echo time(); ?>"></script>
<script src="js/datepicker.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$("body").keydown(function(e) {
  if(e.which == 37) { // left
    var cursno = document.getElementById("usergallerycurrentsno").value;
    console.log(cursno);
    if(cursno!='')
    {
      <?php
      if(@$_REQUEST['enduserid']!='')
      {
        ?>
        var enduserid = <?php echo @$_REQUEST['enduserid']; ?>;
        prevgaluser(cursno,enduserid);
        <?php
      }
      else
      {
        ?>
        var enduserid = "";
        prevgalfooter(cursno);
        <?php
      }
      ?>
    }
  }
  else if(e.which == 39) { // right

    var cursno = document.getElementById("usergallerycurrentsno").value;

    if(cursno!='')
    {
      <?php
      if(@$_REQUEST['enduserid']!='')
      {
        ?>
        var enduserid = <?php echo @$_REQUEST['enduserid']; ?>;
        nextgaluser(cursno,enduserid);
        <?php
      }
      else
      {
        ?>
        var enduserid = "";
        nextgalfooter(cursno);
        <?php
      }
      ?>
    }
  }
});
});//]]>
</script>

<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
  $(document).ready(function() {

    $(window).scroll(function () {

      if ($(window).scrollTop() > 260) {
        $('#nav_bar').addClass('navbar-fixed');
      }
      if ($(window).scrollTop() < 261) {
        $('#nav_bar').removeClass('navbar-fixed');
      }
    });
  });
  $(document).click(function (e)
                    {
    if (!$("#notificationContainer").is(e.target) // if the target of the click isn't the container...
        && $("#notificationContainer").has(e.target).length === 0) // ... nor a descendant of the container
    {
      document.getElementById("notificationContainer").style="display:none";
    }
  });
});//]]>
/*$(document).click(function(event) {
  console.log(event.target.closet());
  if((event.target.closest("#search")==null) &&  (event.target.closest("table")==null))
  {
    closesearch();
  }
  if(event.target.closest("#notificationContainer")==null)
  {
    $("#notificationContainer").hide();
  }

});*/
</script>

<script type="text/javascript">
  $("notificationContainer").click(function()
  {
    $("#notificationContainer").fadeToggle(300);


  });


  </script>
  <script type="text/javascript">
  function customddate()
  {
    if((document.getElementById('cutomisedate').checked)==true)
    {
      document.getElementById('daterange').style.display="block";
      document.getElementById('dropdowndaterange').style.display="none";
      document.getElementById('cutomisedate').value="1";
    }
    else
    {
      document.getElementById('daterange').style.display="none";
      document.getElementById('dropdowndaterange').style.display="block";
      document.getElementById('cutomisedate').value="0";
    }
  }
  </script>
    <style>
	#pavdescription{height:85%;width:100%;overflow:hidden;position:absolute}
	.readmoreclass{max-height:auto;overflow:hidden}
	.closemoreclass{max-height:120px;overflow:hidden}
	.overlay{background-color:#000;opacity:.65;*background:none;z-index:9999;width:100%}
    </style>
    <script type="text/javascript">
      $(document).ready(function ($) {
        $('#pavdescription').perfectScrollbar();
      });
    </script>

</head>
