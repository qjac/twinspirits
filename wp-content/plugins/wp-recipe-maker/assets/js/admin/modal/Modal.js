import Recipe from './Recipe';
import modalInit from './ModalInit';

let Modal = {
    changes_made: false,
    container: false,
    active_editor_id: false,
    actions: {},
    recipe: Recipe,
    init: function(container) {
        this.container = container;
        modalInit(this);
        this.recipe.init();
    },
    open: function(editor_id, args = {}) {
		// Enable menu items
		jQuery('.wprm-menu-item').show();
		jQuery('.wprm-menu-hidden').hide();

		this.active_editor_id = editor_id;
		jQuery('.wprm-modal-container').show();

		// Init tabs
		var tabs = jQuery('.wprm-router').find('.wprm-menu-item');
		jQuery(tabs).each(function() {
            let init_callback = jQuery(this).data('init');

            if (init_callback && typeof Modal.actions[init_callback] == 'function') {
                Modal.actions[init_callback](args);
            }
		});

		// Default to first menu item
        jQuery('.wprm-menu').find('.wprm-menu-item').first().click();
        
        this.changes_made = false;
    },
    close: function() {
        this.active_editor_id = false;
		jQuery('.wprm-menu').removeClass('visible');
		jQuery('.wprm-modal-container').hide();
    },
    disable_menu: function() {
		jQuery('.wprm-frame-menu').find('.wprm-menu-item').hide();
		jQuery('.wprm-menu-hidden').show();
    },
}
export default Modal;