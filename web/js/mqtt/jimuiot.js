$(document).ready(function(){
    $("#hexInput").bind("change",function(){  
        $("#send_form_input").val("");
        $("#messages").text("");
    });

    $("#hexInputSend").bind("change",function(){  
        $("#send_form_input").val("");
        $("#messages").text("");
    });

    $('#send_form_input').bind('input propertychange', function() {
        var hexChecked = $("#hexInputSend").attr("checked");
        if (hexChecked) {
            text = $("#send_form_input").val();
            len = $("#send_form_input").val().length;

            var reg = /\s/g;
            var text = text.replace(reg, "");

            $("#send_form_input").val(text.toUpperCase());

            if (len > 0) {
                $("#send_form_input").css("background-color","#F2F7AD");
            } else {
                $("#send_form_input").css("background-color","white");
            }
            // $('#send_form_input').html($(this).val().length + ' characters');  
            
            // var textArray = text.split('');
            var formatText = text;

            var isHex = true;

            for (var i = text.length - 1; i >= 0; i--) {
                // console.log(text[i]);
                var textHex = text[i];
                // console.log(textHex);

                var n = parseInt("0x" + textHex);
                // console.log("0x" + textHex);



                if (!n && n !== 0) {
                    isHex = false;
                    formatText = text.substring(0, i);
                }

                // if (textHex.charCodeAt()) {
                // }

                if (i == 0 && !isHex) {
                // if (i == 0) {
                //     var formatTextWithSpace = "";
                //     for (var j = 0; j < formatText.length; j++) {
                //         if ( j !== 0 && j%2 == 0){
                //             formatTextWithSpace = formatTextWithSpace + " ";
                //         } else {
                //             formatTextWithSpace = formatTextWithSpace + formatText[j];
                //         }
                //     }

                    $("#send_form_input").val(formatText);
                    isHex = true;
                }
            };
        }
    });
});