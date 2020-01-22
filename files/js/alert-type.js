function deleteWordFun(id) {
    if(confirm("This Can not be UNDO!")) {
        jQuery.ajax({
            url: 'alert-type-submit.php?fun=deleteWordFun',
            method: 'POST',
            data: {"id": id},
            dataType: 'json'
        }).fail(function(data) {
            console.log('failed', data);
        }).done(function(data) {
            $('#'+ id).fadeOut("slow", function() {
                $(this).remove();
            });

        });
    }
}

$(document).ready(function() {
    $('#wordBtn').click(function() {
        let word = $('#word').val();
        if(word != "") {
            $('#word').css("border", "");

            let wordOption = $('#wordOption').val();
            if($('#wordTyped').is(":checked")) {
                $.fn.addWordIntoTbl(1, word, 1, wordOption);
            }
            if($('#wordCopied').is(":checked")) {
                $.fn.addWordIntoTbl(1, word, 2, wordOption);
            }
        } else {
            $('#word').css("border", "1px solid #ff0000");
        }
	});

    $('#fileBtn').click(function() {
        let file_name = $('#file_name').val();
        if(file_name != "") {
            $('#file_name').css("border", "");

            let fileOption = $('#fileOption').val();
            if($('#fileCopied').is(":checked")) {
                $.fn.addWordIntoTbl(2, file_name, 2, fileOption);
            }
            if($('#fileDeleted').is(":checked")) {
                $.fn.addWordIntoTbl(2, file_name, 3, fileOption);
            }
        } else {
            $('#file_name').css("border", "1px solid #ff0000");
        }
	});

    $('#appTitleBtn').click(function() {
        let app_title = $('#app_title').val();
        if(app_title != "") {
            $('#app_title').css("border", "");

            let appTitleOption = $('#appTitleOption').val();
            $.fn.addWordIntoTbl(3, app_title, 0, appTitleOption);
        } else {
            $('#app_title').css("border", "1px solid #ff0000");
        }
	});

    $('#appRunBtn').click(function() {
        let app_Run = $('#app_Run').val();
        if(app_Run != "") {
            $('#app_Run').css("border", "");

            let appRunOption = $('#appRunOption').val();
            $.fn.addWordIntoTbl(4, app_Run, 0, appRunOption);
        } else {
            $('#app_Run').css("border", "1px solid #ff0000");
        }
	});

    $('#urlBtn').click(function() {
        let url_title = $('#url_title').val();
        if(url_title != "") {
            $('#url_title').css("border", "");

            $.fn.addWordIntoTbl(5, url_title, 0, 0);
        } else {
            $('#url_title').css("border", "1px solid #ff0000");
        }
	});


    $.fn.addWordIntoTbl = function(alert_type, word, wordType, wordOption) {
        jQuery.ajax({
    		url: 'alert-type-submit.php?fun=addWord',
    		method: 'POST',
    		data: {"alert_type": alert_type, "word": word, "wordType": wordType, "wordOption": wordOption},
    		dataType: 'json'
    	}).fail(function(data) {
    		console.log('failed', data);
    	}).done(function(data) {
            //console.log(data);
            let dataArr = JSON.parse( JSON.stringify(data) );
            if(alert_type == 1) {
                var textField = "#word";
                var msgDiv = ".wordMsg";
                var outputDiv = "#wordOutput";
            } else if(alert_type == 2) {
                var textField = "#file_name";
                var msgDiv = ".fileMsg";
                var outputDiv = "#fileOutput";
            } else if(alert_type == 3) {
                var textField = "#app_title";
                var msgDiv = ".appTitleMsg";
                var outputDiv = "#appTitleOutput";
            } else if(alert_type == 4) {
                var textField = "#app_Run";
                var msgDiv = ".appRunMsg";
                var outputDiv = "#appRunOutput";
            } else { //5
                var textField = "#url_title";
                var msgDiv = ".urlMsg";
                var outputDiv = "#urlOutput";
            }

            if(dataArr.status == 200) {
                $(outputDiv).append(dataArr.result);
            }

            $(textField).val("");
            $(msgDiv).html(dataArr.msg).fadeIn('slow');
            setTimeout(function() {
                $(msgDiv).fadeOut();
            }, 1000);
    	});
	};






});
