<?php
	require '../vendor/autoload.php';
	use Mailgun\Mailgun;
	include_once 'connection.php';
	include("trial_lic.php");
	include("randomlicense.php");
	$mgClient = new Mailgun('8130907ac449ed0c4c83903e6f817c50-41a2adb4-d8ebf029');
	$domain = "hg.endetect.com";

	$currentdate = date("Y-m-d h:i:s",time());
	
	$login_query = $conn->query("insert into U_endowners set name='".$_REQUEST['name']."',
	password='".md5($_REQUEST['user_password'])."',email='".$_REQUEST['user_email']."',phone='".$_REQUEST['phone']."',
	company='".$_REQUEST['company_name']."',jdt='".$currentdate."',address1='".$_REQUEST['address1']."',
	address2='".$_REQUEST['address2']."',country='".$_REQUEST['country']."',state='".$_REQUEST['state']."',
	city='".$_REQUEST['city']."'");
	
	if($login_query)
	{
		if($_REQUEST['toggle']=='on')
		{		
			
			$totallic = $_REQUEST['licensenum'];	
			$currentdate = date("Y-m-d h:i:s",time());
			$trial_lic_exp = date('Y-m-d h:i:s', strtotime($currentdate.'+1 years'));
			
			$owner_id_query = $conn->query("select sno from U_endowners where email='".$_REQUEST['user_email']."' limit 1");
			$owner_id_arr = $owner_id_query->fetch_assoc();
			$owner_id = $owner_id_arr['sno'];
			
			$license_query = $conn->query("insert into U_license set lickey='".$licensekey."',
			owner_id='".$owner_id."',total_lic='".$totallic."',paid='1',
			trial='0',act_date='".$currentdate."',exp_date='".$trial_lic_exp."'");
			$_SESSION['signup']=1;
		}
		else
		{
			$license_query = $conn->query("insert into U_license set lickey='".$licensekey."',
			owner_id='".$owner_id."',total_lic='".$totallic."',paid='0',
			trial='1',act_date='".$currentdate."',exp_date='".$trial_lic_exp."'");
			$_SESSION['signup']=1;
		}
		$emailcom 	= base64_encode($_REQUEST["user_email"]);
		$verifylink = baseurl."verifyemail.php?v=".$emailcom;
		ob_start();
		?>
			<div style="width:100%!important;margin:0;padding:0;border:0;background-color:#f2f2f2">
		      <table style="min-width:320px;border-collapse:collapse;border-spacing:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f2f2f2">
		        <tbody><tr>
		          <td style="padding:25px 10px">
		            <table style="border-collapse:collapse;border-spacing:0;font-family:Helvetica Neue,Helvetica,Arial,sans-serif" width="580" cellspacing="0" cellpadding="0" border="0" align="center">
		              
		              <tbody><tr>
		                <td style="padding:0 0 25px 0"><a href=""><img src="<?php echo baseurl; ?>images/endetectlogo.png"></a></td>
		              </tr>
		              
		              <tr>
		                <td style="border-radius:8px 8px 0 0" bgcolor="#2c343c"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 720.5px; top: 315px;"><div id=":n3" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download"><div class="aSK J-J5-Ji aYr"></div></div></div></td>
		              </tr>
		              
		              <tr>
		                <td style="padding:50px 50px 0 50px" bgcolor="#ffffff">
		                  <h1 style="margin:0 0 33px 0;color:#2c343c;font-size:40px;line-height:40px">Hey,</h1>
		                  <p style="margin:0 0 10px 0;color:#2c343c;font-size:24px;line-height:40px">Your account is almost ready!</p>
		                  <p style="margin:0 0 10px 0;color:#2c343c;font-size:24px;line-height:40px">We just need to verify your email address</p>
		                  <p style="margin:0 0 40px 0;color:#2c343c;font-size:24px;line-height:40px">in order to complete your signup.</p>
		                  <p style="margin:20 0 10px 0;color:#2c343c;font-size:24px;line-height:40px"><a href="<?php echo $verifylink; ?>" style="display:block;width:230px;height:60px;margin:0;background-color:#48b0f7;border-radius:4px;color:#fff;font-size:18px;line-height:60px;text-align:center;text-decoration:none!important" target="_blank" data-saferedirecturl=""><strong style="font-weight:400;text-decoration:none!important">Verify email address</strong></a></p>
		                </td>
		              </tr>
		              <tr>
		                <td style="margin:30px 0 0">
		                  <table style="border-collapse:collapse;border-spacing:0" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
		                    <tbody>
		                      <tr>
		                        <td style="padding:20px 50px 0 50px;line-height:30px" width="50%" valign="middle"><strong style="font-weight:400px;text-decoration:none!important">
		                            <u></u>If you have not created an account with EnDetect,<br><u></u></strong><strong style="font-weight:400px;text-decoration:none!important">
		                            <u></u>Please ignore this email.<br><u></u></strong></td>
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
		                              <u></u>Team EnDetect<u></u></strong></a><br>
		                              <a href="mailto:info@EnDetect.com" style="color:#48b0f7;font-size:16px;line-height:30px;text-decoration:none!important" target="_blank"><strong style="font-weight:400;text-decoration:none!important">
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

    	$result = $mgClient->sendMessage("$domain",
       	array('from'    => 'Endetect<info@endetect.com>',
             'to'      => $_REQUEST["user_email"] ,
             'subject' => 'Verify Your EnDetect Account',
             'html'    => $output));
	}
	$conn->close();
	
	header("location:../signup.php");
?>