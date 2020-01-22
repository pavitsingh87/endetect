jQuery(document).ready(function() {
    autosize();
    boxer();
    $("#searchInput").keyup(function() {
        var a = $("#searchInput").val();
        if ("#" == a) {
            $(".search-container").hide();
            $(".search-content").remove();
            return false;
        }
        if (a.indexOf("#") === -1) {
            var b = "q";
            var c = "people";
        } else {
            var b = "tag";
            var c = "tags";
        }
        if (0 == a) var d = 0; else {
            $(".search-container").show();
            $(".search-container").html('<div class="search-content"><div class="search-results"><div class="message-inner"><div class="retrieving-results">Retrieving Results</div> <div class="preloader-retina preloader-left"></div></div></div></div>');
            var d = 200;
        }
        setTimeout(function() {
            if (a == $("#searchInput").val()) if (0 == a) {
                $(".search-container").hide();
                $(".search-content").remove();
            } else $.ajax({
                type: "POST",
                url: "services/usersearch.php",
                data: "q=" + a + "&start=1&live=1",
                cache: false,
                success: function(a) {
                    $(".search-container").html(a).show();
                }
            });
        }, d);
    });
});

var geocoder;

function validate_videourl() {
    var a = jQuery("#form-value").val();
    var b = a.search("youtu");
    var c = a.search("vimeo");
    var d = a.search("soundcloud");
    var e = a.search("snd");
    var f = a.search("metacafe");
    var g = a.search("dailymotion");
    if (b != -1 || c != -1 || d != -1 || e != -1 || f != -1 || g != -1) {
        if (b != -1) jQuery("#video_type").val("youtube");
        if (c != -1) jQuery("#video_type").val("vimeo");
        if (d != -1) jQuery("#video_type").val("soundcloud");
        if (e != -1) jQuery("#video_type").val("soundcloud");
        if (f != -1) jQuery("#video_type").val("metacafe");
        if (g != -1) jQuery("#video_type").val("dailymotion");
        return true;
    } else {
        alert("Enter Youtube/Vimeo/Soundcloud/Metacafe/Dailymotion url");
        return false;
    }
}

function share_post(a) {
    $("#share").show("slow");
    $(".share-close").hide();
    $(".share-btn").show();
    $(".share-cancel").show();
    $(".share-btn").attr("onclick", "invoke_share_post(" + a + ", 1)");
    $(".share-cancel").attr("onclick", "invoke_share_post(0, 0)");
}

function invoke_share_post(a, b) {
    if (b) {
        $(".share-btn").hide();
        $(".share-cancel").hide();
        $(".share-close").show();
        $(".share-desc").html('<div class="preloader-retina-large preloader-center"></div>');
        $.ajax({
            type: "POST",
            url: domain + "post/share",
            data: "id=" + a,
            cache: false,
            success: function(a) {
                $(".share-desc").html(a);
            }
        });
    } else $("#share").hide("slow");
}

function get_geo_location() {
    if (navigator.geolocation) navigator.geolocation.getCurrentPosition(successFunction, errorFunction); else return false;
}

function successFunction(a) {
    var b = a.coords.latitude;
    var c = a.coords.longitude;
    codeLatLng(b, c);
}

function errorFunction() {
    alert("Geocoder failed");
}

function initialize() {
    geocoder = new google.maps.Geocoder();
}

initialize();

function codeLatLng(a, b) {
    var c = new google.maps.LatLng(a, b);
    geocoder.geocode({
        latLng: c
    }, function(a, b) {
        if (b == google.maps.GeocoderStatus.OK) {
            console.log(a);
            if (a[1]) jQuery("#form-value").val(a[0].formatted_address); else return false;
        } else return false;
    });
}

function startUpload() {
    if ($("#video").is(":checked")) if (!validate_videourl()) return false;
    document.getElementById("imageForm").target = "w3_iframe";
    document.getElementById("imageForm").submit();
    document.getElementById("post-loader9999999999").style.visibility = "visible";
}

function stopUpload(a) {
    document.getElementById("post-loader9999999999").style.visibility = "hidden";
    document.getElementById("load-content").innerHTML = a + document.getElementById("load-content").innerHTML;
    document.getElementById("imageForm").reset();
    document.getElementById("post9999999999").style.height = "38px";
    document.getElementById("queued-files").innerHTML = "0";
    $("#values label").addClass("selected").siblings().removeClass("selected");
    $(".message-form-input").hide("slow");
    autosize();
    boxer();
    return true;
}

function focus_form(a) {
    document.getElementById("comment-form" + a).focus();
    showButton(a);
}

function doLike(a, b) {
    $("#like_btn" + a).html('<div class="privacy_loader"></div>');
    $("#doLike" + a).removeAttr("onclick");
    $.ajax({
        type: "POST",
        url: domain + "post/like",
        data: "id=" + a + "&type=" + b,
        cache: false,
        success: function(b) {
            $("#message-action" + a).empty();
            $("#message-action" + a).html(b);
        }
    });
}

function delete_item(a, b) {
    var c = confirm("Are you sure to delete this item?");
    if (c) {
        if (0 == b) $("#del_comment_" + a).html('<div class="preloader-retina"></div>'); else if (1 == b) $("#del_message_" + a).html('<div class="preloader-retina-large preloader-center"></div>'); else if (2 == b) $("#del_chat_" + a).html('<div class="preloader-retina"></div>');
        $.ajax({
            type: "POST",
            url: domain + "post/delete",
            data: "id=" + a + "&type=" + b,
            cache: false,
            success: function(c) {
                if ("1" == c) {
                    if (0 == b) $("#comment" + a).fadeOut(500, function() {
                        $("#comment" + a).remove();
                    }); else if (1 == b) $("#message" + a).fadeOut(500, function() {
                        $("#message" + a).remove();
                    }); else if (2 == b) $("#chat" + a).fadeOut(500, function() {
                        $("#chat" + a).remove();
                    });
                } else if (0 == b) $("#comment" + a).html($("#del_comment_" + a).html("Sorry, the comment could not be removed, please refresh the page and try again.")); else if (1 == b) $("#message" + a).html($("#del_message_" + a).html('<div class="message-content"><div class="message-inner">Sorry, the message could not be removed, please refresh the page and try again.</div></div>')); else if (2 == b) $("#chat" + a).html($("#del_chat_" + a).html("Sorry, the chat message could not be removed, please refresh the page and try again."));
            }
        });
    }
}

function loadMoreComments(a, b, c) {
    $("#more_comments_" + a).html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "loadajax/comments",
        data: "id=" + a + "&start=" + c + "&cid=" + b,
        cache: false,
        success: function(b) {
            $("#more_comments_" + a).remove();
            $("#comments-list" + a).prepend(b);
        }
    });
}

function postChatmessage(a) {
    var b = $("textarea#chat").val();
    var c = $("#postchat" + a).serialize();
    if (b) {
        $(".message-loader").show();
        document.getElementById("chat").style.height = "45px";
        $("textarea#chat").val("");
        $.ajax({
            type: "POST",
            url: domain + "post/chat",
            data: c,
            cache: false,
            success: function(a) {
                $(".chat-container").append(a);
                $(".message-loader").hide();
                $(".chat-container").scrollTop($(".chat-container")[0].scrollHeight);
                checkNewMessage(1);
            }
        });
    } else {
        alert("Message cannot be empty");
        $("textarea#chat").focus();
    }
}

function postComment(a) {
    var b = $("#comment-form" + a).val();
    $("#post_comment_" + a).html('<div class="preloader-retina-large preloader-center"></div>');
    $("#comment_btn_" + a).fadeOut("slow");
    $.ajax({
        type: "POST",
        url: domain + "post/comment",
        data: "id=" + a + "&comment=" + encodeURIComponent(b),
        cache: false,
        success: function(b) {
            $("#post_comment_" + a).html("");
            $("#comments-list" + a).append(b);
            $(".message-reply-container").fadeIn(500);
            $("#comment-form" + a).val("");
        }
    });
}

function get_global_notifications() {
    $("#global_notification").html("&nbsp;");
    $("#new_msg_boxs").html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "loadajax/notification",
        cache: false,
        success: function(a) {
            $("#new_msg_boxs").html(a);
        }
    });
}

function get_friend_requests() {
    $("#request_box").html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "loadajax/friend_requests",
        cache: false,
        success: function(a) {
            $("#request_box").html(a);
        }
    });
}

function get_likes(post_id) {
	$.prettyPhoto.open(domain+'loadajax/getlikes/'+post_id+'?iframe=true&amp;width=50%&amp;height=50%','People who Likes this post','');
}

function get_unread_msgs() {
    $("#new_msg_box").html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "loadajax/check_new_msg",
        cache: false,
        success: function(a) {
            $("#new_msg_box").html(a);
        }
    });
}

function manage_friend(a, b) {
    $("#list-" + a).html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "loadajax/manage_friend",
        data: "id=" + a + "&type=" + b,
        cache: false,
        success: function(b) {
            $("#list-" + a).html(b);
            var c = parseInt($("#notification_count").html()) - 1;
            if (c <= 0) $("#notification_count").hide(); else $("#notification_count").html(c);
        }
    });
}

function friend_relation(a, b, c) {
    $("#friend_relation" + c).html('<div class="privacy_loader"></div>');
    $.ajax({
        type: "POST",
        url: domain + "post/friend_relation",
        data: "id=" + a + "&type=" + b + "&divid=" + c,
        cache: false,
        success: function(a) {
            $("#friend_relation" + c).html(a);
        }
    });
}

$(function() {
    $("#values input:radio").addClass("input_hidden");
    $("#values label").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
        $("#form-value").attr("Placeholder", $(this).attr("title"));
        $("#form-value").val("");
        $("#my_file").val("");
        $(".message-form-input").show("slow");
        $(".selected-files").hide("slow");
    });
    $("#my_file").click(function() {
        $("#form-value").val("");
        $(".message-form-input").hide("slow");
        $(".selected-files").show("slow");
        $("#values label").removeClass("selected");
    });
    $(":file").change(function() {
        $("#queued-files").text(this.files.length);
    });
    $("#imageForm").submit(function() {
        return false;
    });
});