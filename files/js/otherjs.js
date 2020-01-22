$(document).ready(function()
{
	
	$("#notificationLink").click(function()
	{
		if(document.getElementById("notificationContainer").style.display=='none')
		{
			document.getElementById("notificationContainer").style.display="block";
			document.getElementById("notificationcount").style.display="none";
			document.getElementById("notifycnt").value="0";
			notify('<?php echo $_SESSION["ownerid"] ?>');
		}
		else
		{
			document.getElementById("notificationContainer").style.display="none";
		}
			
		
		$("#notification_count").fadeOut("slow");
		$("#logoutContainer").hide();
		return false;
	});
	$("#notificationClose").click(function()
	{		
	
		$("#notificationContainer").hide();
	});
	
	//Document Click
	$(document).click(function()
	{		
		$("#notificationContainer").hide();
	});
	//Popup Click
	$("#notificationContainer").click(function()
	{
		return false
	});
	$("#logoutLink").click(function()
	{
		$("#logoutContainer").fadeToggle(300);
		$("#notificationContainer").hide();
		return false;
	});

	//Document Click
	$(document).click(function()
	{
		$("#logoutContainer").hide();
	});
	//Popup Click
	$("#logoutContainer").click(function()
	{
		return false
	});
});

$(document).ready(function ($) {
$('#pavdescription').perfectScrollbar();
});

$(document).ready(function()
{
	$('#commentsToggle').click(function(){					
	    $('#pocp2').toggleClass('hidden');
	    $('.commentsToggle').toggleClass('showin');
	});
	
	
});

$(window).load(function(){
	$('.parentDiv').click(function(event){
	   event.stopPropagation();
	   var idtocheck = event.target.id;
	   var getidtocheck = idtocheck.split("-");
		if(getidtocheck['0']=='childdiv')
		{
			
			blanket_size('popUpDiv');
			window_pos('popUpDiv');
			toggle('blanket');
			toggle('popUpDiv');
			document.getElementById("loadondemandsnap").innerHTML="";
			document.getElementById('loadinggif2').style.display="block";
				 
			snapshot(getidtocheck['1'],"21");
			notifycntupdate(document.getElementById('notifycnt').value);
					   		
		}
		else
		{
			window.location.href="userprofile.php?enduserid="+getidtocheck['1'];
		}
	});
});
$( "#hide-cont" ).click(function() {
	
	$( "#sidebar-container" ).slideToggle( "slow" );
	$( "#show-cont" ).css("display", "block");
		$(window).scroll(function () {
		    //if you hard code, then use console
		    //.log to determine when you want the 
		    //nav bar to stick.  
		    console.log($(window).scrollTop())
		  if ($(window).scrollTop() > 30) {
		    $('#nav_bar').addClass('navbar-fixed');
		  }
		  if ($(window).scrollTop() < 31) {
		    $('#nav_bar').removeClass('navbar-fixed');
		  }
		});
	});
	$( "#show-cont" ).click(function() {
		$( "#show-cont" ).css("display", "none");	
		document.getElementById('streamuserres').value='0';
	$( "#sidebar-container" ).slideToggle( "slow" );
	});
	
		window.onkeyup = function (event) {
			if (event.keyCode == 27) {
				closepopup();
			}
		}