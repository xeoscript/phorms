/*
 Using WordPress Media Uploader System with plugin settings
 Author: oneTarek
 Author URI: http://onetarek.com
 */

jQuery(function () {
    var form_field;

    // adding my custom function with Thick box close function tb_close() .
    window.old_tb_remove = window.tb_remove;
    window.tb_remove = function () {
        window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
        form_field = null;
    };

    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
        if (form_field) {
            fileurl = jQuery('img', html).attr('src');
            jQuery(form_field).val(fileurl);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };

    jQuery('.select-media-widget').each(function () {
        /* user clicks button on custom field, runs below code that opens new window */
        jQuery(this).click(function () {
            form_field = jQuery(this).prev('input'); //The input field that will hold the uploaded file url
            tb_show('', 'media-upload.php?TB_iframe=true');
            return false;

        });
    });
});