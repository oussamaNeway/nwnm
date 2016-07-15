

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:


        $("#fileuploadHtml").fileupload({
            url: '/netmessage/public/server/php/',
            dropZone: $(this),
            autoUpload: true,

            done : function(e, data){
                if(data.files[0].type == "text/html"){
                    var url = data.result.files[0].url;
                    var newUrl ="/"+url.substring(url.lastIndexOf("public"),url.lastIndexOf("files"))+"files/"+data.result.files[0].name;
                    socket.emit('importHtmlServer',newUrl);
                }
            }
        });

});
