

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:



    // Enable iframe cross-domain access via redirect option:
    $('.dragImgTemplate').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('.dragImgTemplate').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 999000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                    new Date())
                    .appendTo('.dragImgTemplate');
            });
        }
    } else {
        // Load existing files:
        $('.dragImgTemplate').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('.dragImgTemplate').fileupload('option', 'url'),
            dataType: 'json',
            context: $('.dragImgTemplate')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {

            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
    }

});
