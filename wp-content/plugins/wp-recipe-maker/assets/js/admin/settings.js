import '../../../vendor/select2/js/select2.min.js';
import '../../../vendor/select2/css/select2.min.css';
import '../../css/admin/settings.scss';

jQuery(document).ready(function($) {
	jQuery('.wprm-settings').find('select').select2_wprm();
	jQuery('.wprm-settings').find('.wprm-color').wpColorPicker();

	jQuery('#reset_settings_to_default').on('click', function() {
		var checkbox = jQuery(this);
		if(checkbox.is(':checked')) {
            alert('Warning: having this checked will reset all your settings to default again');
        }
	});
});
