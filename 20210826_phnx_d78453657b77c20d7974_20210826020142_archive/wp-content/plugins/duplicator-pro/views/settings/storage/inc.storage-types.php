<?php
defined("ABSPATH") or die("");

$global = DUP_PRO_Global_Entity::get_instance();
?>
<form id="dup-settings-form" action="<?php echo DUP_PRO_CTRL_Storage_Setting::getSubTabURL(DUP_PRO_CTRL_Storage_Setting::TAB_STORAGES); ?>" method="post" data-parsley-validate>
    <?php require('hidden.fields.widget.php'); ?>
    <!-- ===============================
    GDRIVE SETTINGS -->
    <h3 class="title"><?php DUP_PRO_U::esc_html_e("Google Drive") ?></h3>
    <hr size="1" />
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::esc_html_e("Upload Size (KB)"); ?></label></th>
            <td>
                <input class="narrow-input" 
                       type="number"
                       min="256"
                       name="gdrive_upload_chunksize_in_kb"
                       id="gdrive_upload_chunksize_in_kb"
                       data-parsley-required
                       data-parsley-type="number"
                       data-parsley-errors-container="#gdrive_upload_chunksize_in_kb_error_container"
                       value="<?php echo esc_attr($global->gdrive_upload_chunksize_in_kb); ?>" />
                <div id="gdrive_upload_chunksize_in_kb_error_container" class="duplicator-error-container"></div>
                <p class="description">
                    <?php DUP_PRO_U::esc_html_e('How much should be uploaded to Google Drive per attempt. Higher=faster but less reliable. It should be multiple of 256.'); ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- ===============================
    DROPBOX SETTINGS -->
    <h3 class="title"><?php DUP_PRO_U::esc_html_e("Dropbox") ?> </h3>
    <hr size="1" />
    <table class="form-table">        
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::esc_html_e("Transfer Mode"); ?></label></th>
            <td>
                <input type="radio" value="<?php echo DUP_PRO_Dropbox_Transfer_Mode::Disabled ?>" name="dropbox_transfer_mode" id="dropbox_transfer_mode_disabled" <?php echo DUP_PRO_UI::echoChecked($global->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::Disabled); ?> >
                <label for="dropbox_transfer_mode_disabled"><?php DUP_PRO_U::esc_html_e("Disabled"); ?></label> &nbsp;

                <input type="radio" <?php DUP_PRO_UI::echoDisabled(!DUP_PRO_Server::isCurlEnabled()) ?> value="<?php echo DUP_PRO_Dropbox_Transfer_Mode::cURL ?>" name="dropbox_transfer_mode" id="dropbox_transfer_mode_curl" <?php echo DUP_PRO_UI::echoChecked($global->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::cURL); ?>/>
                <label for="dropbox_transfer_mode_curl">cURL</label> &nbsp;

                <input type="radio" <?php DUP_PRO_UI::echoDisabled(!DUP_PRO_Server::isURLFopenEnabled()) ?> value="<?php echo DUP_PRO_Dropbox_Transfer_Mode::FOpen_URL ?>" name="dropbox_transfer_mode" id="dropbox_transfer_mode_fopen" <?php echo DUP_PRO_UI::echoChecked($global->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::FOpen_URL); ?>/>
                <label for="dropbox_transfer_mode_fopen">FOpen URL</label> &nbsp;
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::esc_html_e("Upload Size (KB)"); ?></label></th>
            <td>
                <input class="narrow-input" 
                       type="number"
                       min="100"
                       name="dropbox_upload_chunksize_in_kb"
                       id="dropbox_upload_chunksize_in_kb"
                       data-parsley-required
                       data-parsley-type="number"
                       data-parsley-errors-container="#dropbox_upload_chunksize_in_kb_error_container"
                       value="<?php echo esc_attr($global->dropbox_upload_chunksize_in_kb); ?>" />
                <div id="dropbox_upload_chunksize_in_kb_error_container" class="duplicator-error-container"></div>
                <p class="description">
                    <?php DUP_PRO_U::esc_html_e('How much should be uploaded to Dropbox per attempt. Higher=faster but less reliable.'); ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- ===============================
    S3 SETTINGS -->
    <h3 class="title"><?php DUP_PRO_U::esc_html_e("Amazon S3") ?></h3>
    <hr size="1" />
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::esc_html_e("Upload Size (KB)"); ?></label></th>
            <td>
                <input class="narrow-input" 
                       type="number"
                       min="<?php echo DUP_PRO_S3_Client_UploadInfo::UPLOAD_PART_MIN_SIZE_IN_K; ?>"
                       max="5243000"
                       name="s3_upload_part_size_in_kb"
                       id="s3_upload_part_size_in_kb"
                       data-parsley-required
                       data-parsley-type="number"
                       data-parsley-errors-container="#s3_upload_chunksize_in_kb_error_container"
                       value="<?php echo esc_attr($global->s3_upload_part_size_in_kb); ?>" />
                <div id="s3_upload_chunksize_in_kb_error_container" class="duplicator-error-container"></div>
                <p class="description">
                    <?php DUP_PRO_U::esc_html_e('How much should be uploaded to Amazon S3 per attempt. Higher=faster but less reliable.'); ?>
                    <?php echo esc_html(sprintf(DUP_PRO_U::__('Min size %skb.'), DUP_PRO_S3_Client_UploadInfo::UPLOAD_PART_MIN_SIZE_IN_K)); ?>
                </p>
            </td>
        </tr>
    </table>

    <!-- ===============================
    OneDrive SETTINGS -->
    <h3 class="title"><?php DUP_PRO_U::esc_html_e("OneDrive") ?></h3>
    <hr size="1" />
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label><?php DUP_PRO_U::esc_html_e("Upload Size (KB)"); ?></label></th>
            <td>
                <input class="narrow-input" 
                       type="number"
                       min="<?php echo intval(DUPLICATOR_PRO_ONEDRIVE_UPLOAD_CHUNK_MIN_SIZE_IN_KB); ?>"
                       name="onedrive_upload_chunksize_in_kb"
                       id="onedrive_upload_chunksize_in_kb"
                       data-parsley-required
                       data-parsley-type="number"
                       data-parsley-errors-container="#onedrive_upload_chunksize_in_kb_error_container"
                       value="<?php echo esc_attr($global->onedrive_upload_chunksize_in_kb); ?>" />
                <div id="onedrive_upload_chunksize_in_kb_error_container" class="duplicator-error-container"></div>
                <p class="description">
                    <?php printf(DUP_PRO_U::esc_html__('How much should be uploaded to OneDrive per attempt. It should be multiple of %dkb. Higher=faster but less reliable.'), DUPLICATOR_PRO_ONEDRIVE_UPLOAD_CHUNK_MIN_SIZE_IN_KB); ?>
                    <?php
                    // https://docs.microsoft.com/en-us/onedrive/developer/rest-api/api/driveitem_createuploadsession?view=odsp-graph-online#upload-bytes-to-the-upload-session
                    printf(
                        DUP_PRO_U::esc_html__('Default size %1$dkb. Min size %2$dkb.'),
                        DUPLICATOR_PRO_ONEDRIVE_UPLOAD_CHUNK_DEFAULT_SIZE_IN_KB,
                        DUPLICATOR_PRO_ONEDRIVE_UPLOAD_CHUNK_MIN_SIZE_IN_KB
                    );
                    ?>
                </p>
            </td>
        </tr>
    </table>
    <p class="submit dpro-save-submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php DUP_PRO_U::esc_attr_e('Save Storage Settings') ?>" style="display: inline-block;" />
    </p>
</form>