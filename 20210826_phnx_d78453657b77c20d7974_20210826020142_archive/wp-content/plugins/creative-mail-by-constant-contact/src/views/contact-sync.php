<?php
use CreativeMail\CreativeMail;
use CreativeMail\Helpers\EnvironmentHelper;
use CreativeMail\Helpers\OptionsHelper;

    $activated_plugins = CreativeMail::get_instance()->get_integration_manager()->get_active_plugins();
    $contact_sync_available = !empty(array_filter($activated_plugins, function ($item) {
        return !$item->is_hidden_from_suggestions();
    }));
    $supported_plugin_available = !empty(CreativeMail::get_instance()->get_integration_manager()->get_supported_integrations(true))
?>

<div class="ce4wp-card">
    <div class="ce4wp-px-4 ce4wp-py-4">
        <h2 class="ce4wp-typography-root ce4wp-typography-h2 ce4wp-mb-2"><?= __( 'Contact Sync', 'ce4wp' ); ?></h2>
        <?php
        if ($contact_sync_available) {
            include 'activated-integrations.php';
        }
        if($supported_plugin_available){
            include 'available-integrations.php';
        }
        include 'creative-mail-form-banner.php'
        ?>
    </div>
</div>
