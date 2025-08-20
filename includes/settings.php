<?php
// Settings page
add_action('admin_menu', function() {
    add_menu_page('Anne\'s Catering', 'Anne\'s Catering', 'manage_options', 'annes-catering', 'afc_settings_page');
});

function afc_settings_page() {
    echo '<div class="wrap"><h1>Anne\'s Catering Settings</h1>';
    echo '<p>Configure deposit %, category badges, and tooltip text here.</p></div>';
}
?>
