$(document).ready(function() //When the dom is ready
{
  $("#user_email").change(function() {
    //if theres a change in the username textbox

    var username = $("#user_email").val(); //Get the value in the username textbox
    var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (filter.test(username)) {
      if (username.length > 3) {
        //if the lenght greater than 3 characters
        $("#availability_status").html(
          '<img src="images/ajax_fb_loader.gif">&nbsp;Checking availability...'
        );
        //Add a loading image in the span id="availability_status"

        $.ajax({
          //Make the Ajax Request
          type: "POST",
          url: "services/emailcheck.php", //file name
          data: "email=" + username, //data
          success: function(server_response) {
            console.log(server_response);
            if (server_response == "0" || server_response == 0) {
              //if ajax_check_username.php return value "0"
              //add this image to the span with id "availability_status"
              $("#ght").removeClass("danger");
              $("#ght").addClass("success");
              $("#ght").html("");
              $("#ght").html('<span class="glyphicon glyphicon-ok"></span>');
              $("#availability_status").html("");
            } else if (server_response == "1") {
              //if it returns "1"
              $("#ght").removeClass("success");
              $("#ght").addClass("danger");
              $("#ght").html();
              $("#ght").html(
                '<span class="glyphicon glyphicon-remove"></span>'
              );
              $("#availability_status").html(
                '<font color="red">Already registered. </font>'
              );
            }
          }
        });
      }
    } else {
      $("#ght").removeClass("success");
      $("#ght").addClass("danger");
      $("#ght").html();
      $("#ght").html('<span class="glyphicon glyphicon-remove"></span>');
      $("#availability_status").html(
        '<font color="red">Enter correct email id.</font>'
      );
    }

    return false;
  });
});
