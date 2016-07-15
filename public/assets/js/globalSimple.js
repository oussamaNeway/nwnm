$(document).ready(function(){

    var editor = CKEDITOR.replace( "editeur" );
    CKEDITOR.on('instanceLoaded', function(e) {
        e.editor.resize('100%', 700)
    } );


    return false;
});
