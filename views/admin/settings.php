<?php
// TODO implement errors
?>
<div class="fids_admin_page_wrapper">
    <h2>General settings</h2>
    <form action="<?php echo get_site_url() . '/wp-admin/admin-post.php'?>" method="post">
        <input type="hidden" name="action" value="fids_update_general_settings">
        <div class="fids_admin_page_input">
            <label for="">APP ID</label>
            <input type="text" name="app_id" value="<?php echo $defaultSettings->app_id ?>">
        </div>
        <div class="fids_admin_page_input">
            <label for="">APP SECRET</label>
            <input type="text" name="app_key" value="<?php echo $defaultSettings->app_key ?>">
        </div>
        <div class="fids_admin_page_input">
            <label for="">Airports <br><small>Comma separated</small></label>
            <input type="text" name="airports" value="<?php echo $defaultSettings->airports ?>">
        </div>
        <div class="fids_admin_page_input fids_mt30">
            <input type="submit" value="UPDATE">
        </div>
    </form>

    <div class="fids_admin_page_fids_elements">
        <h2>Element visibility settings </h2><small>Sort, change titles, mark as visible on the shortcode</small>
        <form action="<?php echo get_site_url() . '/wp-admin/admin-post.php'?>" method="post">
<!--            <div class="fids_admin_page_input fids_mt30">-->
<!--                <input type="submit" value="UPDATE">-->
<!--            </div>-->
            <input type="hidden" name="action" value="fids_update_visible_elements">
            <input id="orderInput" type="hidden" name="order" value="">
            <?php
            $arrivalElements = json_decode($defaultSettings->arrival_elements);
            $departureElements = json_decode($defaultSettings->departure_elements);
            ?>
            <ul id="sortable">
                <?php
                foreach ($elements as $element) {
                ?>
                    <li class="row fids_mt30 fids_mb50 ui-state-default" data-key="<?php echo $element->api_key ?>">
                        <div class="fids_mb20"><b><?php echo $element->api_title ?></b></div>
                        <div class="fids_admin_page_input fids_mb20">
                            <label for="">Custom title <br> <small>will be displayed on the public pages</small></label>
                            <input type="text" name="custom_titles[<?php echo $element->api_key ?>]" value="<?php echo $element->internal_title != '' ? $element->internal_title : $element->api_title; ?>">
                        </div>
                        <div class="fids_admin_page_input fids_flex_row">
                            <input
                                    id="<?php echo $element->api_key . '_arrival'?>"
                                    name="arrivalElements[]"
                                    value="<?php echo $element->api_key?>"
                                    <?php echo in_array($element->api_key, $arrivalElements) ? 'checked="checked"' : '' ?>
                                    type="checkbox">
                            <label for="<?php echo $element->api_key . '_arrival'?>">Show on <b>Arrival</b> section</label>
                        </div>
                        <div class="fids_admin_page_input fids_flex_row">
                            <input
                                    id="<?php echo $element->api_key . '_departure'?>"
                                    name="departureElements[]"
                                    value="<?php echo $element->api_key ?>"
                                    <?php echo in_array($element->api_key, $departureElements) ? 'checked="checked"' : '' ?>
                                    type="checkbox">
                            <label for="<?php echo $element->api_key . '_departure'?>">Show on <b>Departure</b> section</label>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
            <div class="fids_admin_page_input fids_mt30">
                <input type="submit" value="UPDATE">
            </div>
        </form>
    </div>
</div>

<script>
    jQuery( function() {
        jQuery('#sortable').sortable({
            stop: function(event, ui) {
                const data = jQuery('#sortable').sortable('toArray', {
                    attribute: 'data-key'
                });
                console.log(data)
                jQuery('#orderInput').val(data)
            }
        });
    });
</script>

<style>
    .fids_mt30 {
        margin-top: 30px;
    }

    .fids_mb20 {
        margin-bottom: 20px;
    }
    .fids_mb50 {
        margin-bottom: 50px;
    }

    .fids_admin_page_wrapper {
        margin-top: 50px;
    }
    .fids_admin_page_wrapper h2 {
        margin-top: 30px;
    }
    .fids_admin_page_input {
        width: 200px;
        display: flex;
        flex-direction: column;
    }
    .fids_flex_row {
        flex-direction: row;
        align-items: center;
    }
</style>