function autosize() {
	// auto adjust the height of
	$('body').on('keyup', 'textarea', function (){
		$(this).height(0);
		$(this).height(this.scrollHeight);
	});
}

function showButton(id) {
	$('#comment_btn_'+id).fadeIn('slow');
}

function boxer()
{
	$(".boxer").prettyPhoto();
}

autosize();
boxer();
//close notification box
function deleteNotification(id)
{
  $('#notification'+id).fadeOut(500, function() { $('#notification'+id).remove(); });
}



$(function(){
$('.tiptip').poshytip({
	className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	offsetY: 5,
	allowTipHover: true,
	fade: false,
	slide: false
});
	   
/*-----------------search enter----------------------*/
$("#searchInput").on("keyup", function(e){
if(e.keyCode == 13 && jQuery.support.submitBubbles) {
	$(this).next().click();	   
	return false;
}else{
	return true;
}});




/*menu start here*/
  $.fn.fixedMenu = function () {
    return this.each(function () {
      var menu = $(this);
	  
  $("html").click(function(event) {
  if ($(event.target).parents('.list1ul').length==0) {
        menu.find('.active').removeClass('active');
  }

      });
      menu.find('ul li > a').bind('click', function (event) {
        event.stopPropagation();
        //check whether the particular link has a dropdown
        if (!$(this).parent().hasClass('single-link') && !$(this).parent().hasClass('current')) {
          //hiding drop down menu when it is clicked again
          if ($(this).parent().hasClass('active')) {
            $(this).parent().removeClass('active');
          }
          else {
            //displaying the drop down menu
            $(this).parent().parent().find('.active').removeClass('active');
            $(this).parent().addClass('active');
          }
        }
        else {
          //hiding the drop down menu when some other link is clicked
          $(this).parent().parent().find('.active').removeClass('active');
 
        }
      })
    });
  }
/*menu end here*/	

 /*-----------------Profile side link hover----------------------*/
 $(".sideMLinks .not_selected").hover(
  function () {
    $(this).addClass("selectedn");
  },
  function () {
    $(this).removeClass("selectedn");
  }
); 
  	
   
});