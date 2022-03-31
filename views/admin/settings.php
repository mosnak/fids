<div class="fids_admin_page_wrapper">
    <h2>General settings</h2>
    <form action="">
        <div class="fids_admin_page_input">
            <label for="">APP ID</label>
            <input type="text" name="app_key" value="<?php echo $defaultSettings->app_id ?>">
        </div>
        <div class="fids_admin_page_input">
            <label for="">APP SECRET</label>
            <input type="text" name="app_secret" <?php echo $defaultSettings->app_key ?>>
        </div>
        <div class="fids_admin_page_input fids_mt30">
            <input type="submit" value="UPDATE">
        </div>
    </form>

    <div class="fids_admin_page_fids_elements">
        <h2>Element visibility settings</h2>
        <form action="<?php echo get_site_url() . '/wp-admin/admin-post.php'?>" method="post">
            <input type="hidden" name="action" value="update_visible_elements">
            <?php
            $arrivalElements = json_decode($defaultSettings->arrival_elements);
            $departureElements = json_decode($defaultSettings->departure_elements);
            foreach ($fidsElements as $key => $val) {
            ?>
                <div class="row fids_mt30">
                    <div class="fids_mb20"><b><?php echo $val ?></b></div>
                    <div class="fids_admin_page_input fids_flex_row">
                        <input
                                id="<?php echo $key . '_arrival'?>"
                                name="arrivalElements[]"
                                value="<?php echo $key?>"
                                <?php echo in_array($key, $arrivalElements) ? 'checked="checked"' : '' ?>
                                type="checkbox">
                        <label for="<?php echo $key . '_arrival'?>">Show on <b>Arrival</b> section</label>
                    </div>
                    <div class="fids_admin_page_input fids_flex_row">
                        <input
                                id="<?php echo $key . '_departure'?>"
                                name="departureElements[]"
                                value="<?php echo $key?>"
                                <?php echo in_array($key, $departureElements) ? 'checked="checked"' : '' ?>
                                type="checkbox">
                        <label for="<?php echo $key . '_departure'?>">Show on <b>Departure</b> section</label>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="fids_admin_page_input fids_mt30">
                <input type="submit" value="UPDATE">
            </div>
        </form>
    </div>
</div>

<style>
    .fids_mt30 {
        margin-top: 30px;
    }

    .fids_mb20 {
        margin-bottom: 20px;
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