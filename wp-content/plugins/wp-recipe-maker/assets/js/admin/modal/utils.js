import Modal from './Modal';

const shortcode_escape_map = {
	'"': "'",
    '[': '{',
    ']': '}'
};

export function shortcode_escape(text) {
    return String(text).replace(/["]/g, function(s) {
        return shortcode_escape_map[s];
    });
};

export function add_text_to_editor(text) {
    text = ' ' + text + ' ';

    if (Modal.active_editor_id) {
        if (typeof tinyMCE == 'undefined' || !tinyMCE.get(Modal.active_editor_id) || tinyMCE.get(Modal.active_editor_id).isHidden()) {
            var current = jQuery('textarea#' + Modal.active_editor_id).val();
            jQuery('textarea#' + Modal.active_editor_id).val(current + text);
        } else {
            tinyMCE.get(Modal.active_editor_id).focus(true);
            tinyMCE.activeEditor.selection.collapse(false);
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, text);
        }
    }
};

export function select_media_image(container) {
    // Create a new media frame (don't reuse because we have multiple different inputs)
    let frame = wp.media({
        title: wprm_temp_admin.modal.text.media_title,
        button: {
            text: wprm_temp_admin.modal.text.media_button
        },
        multiple: false
    });


    // When an image is selected in the media frame...
    frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        set_media_image(container, attachment.id, attachment.url);
    });

    // Finally, open the modal on click
    frame.open();
};

export function set_media_image(container, image_id, image_url) {
    container.find('.wprm-recipe-image-preview').html('');
    container.find('.wprm-recipe-image-preview').append('<img src="' + image_url + '" />');
    container.find('input').val(image_id);

    container.find('.wprm-recipe-image-add').addClass('hidden');
    container.find('.wprm-recipe-image-remove').removeClass('hidden');
    Modal.changes_made = true;
};

export function remove_media_image(container) {
    container.find('.wprm-recipe-image-preview').html('');
    container.find('input').val('');

    container.find('.wprm-recipe-image-add').removeClass('hidden');
    container.find('.wprm-recipe-image-remove').addClass('hidden');
    Modal.changes_made = true;
};

export function start_loader(button) {
    button
        .prop('disabled', true)
        .css('width', button.outerWidth())
        .data('text', button.html())
        .html('...');
};

export function stop_loader(button) {
    button
        .prop('disabled', false)
        .css('width', '')
        .html(button.data('text'));
};