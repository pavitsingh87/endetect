$(function(){
		   
		   
//for chose industry for employeed 	
$('.status_chooser_btn').on('change', function() {
	if($(this).val()=='employed'){
		$('#status_chooser_employed').show();
	}else{
		 $('#status_chooser_employed').hide();
		 $('#industry, #headline').val('');
	}	
});	


if($('.status_chooser_btn:checked').val()=='employed'){
	$('#status_chooser_employed').show();	
}

		   

/*-----------------top Login enter----------------------*/
$('#pass').keyup(function(e) {
if(e.keyCode == 13 && jQuery.support.submitBubbles) {
          $("#login_form").submit(); 
        }
});

/*-----------------Signup enter----------------------*/
$('#reg_pass').keyup(function(e) {
if(e.keyCode == 13 && jQuery.support.submitBubbles) {
          $("#signup").submit(); 
        }
});
	

	
	
	
});
