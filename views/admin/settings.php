<?php
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
        <h4>Sort query params <small>(will fallback to first element from the list if not selected)</small></h4>
        <?php
        $sortItems = array_merge(json_decode($defaultSettings->departure_elements), json_decode($defaultSettings->arrival_elements));
        ?>
        <div class="fids_admin_page_input">
            <label for="">Sort 1 <small>Applied value: <?php echo $sort1 != '' ? $sort1 : 'No applied value' ?></small></label>
            <select name="sort1" id="">
                <?php
                foreach ($sortItems as $si) { ?>
                    <option <?php echo $si == $sort1 ? 'selected' : '' ?> value="<?php echo $si ?>"><?php echo $si ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="fids_admin_page_input">
            <label for="">Sort 2 <small>Applied value: <?php echo $sort2 != '' ? $sort2 : 'No applied value' ?></small></label>
            <select name="sort2" id="">
                <?php
                foreach ($sortItems as $si) { ?>
                    <option <?php echo $si == $sort2 ? 'selected' : ''  ?> value="<?php echo $si ?>"><?php echo $si ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="fids_admin_page_input">
            <label for="">Sort 3 <small>Applied value: <?php echo $sort3 != '' ? $sort3 : 'No applied value'?></small></label>
            <select name="sort3" id="">
                <?php
                foreach ($sortItems as $si) { ?>
                    <option <?php echo $si == $sort3 ? 'selected' : ''  ?> value="<?php echo $si ?>"><?php echo $si ?></option>
                    <?php
                }
                ?>
            </select>
        </div>


        <h4>General query params <small>(leave empty if don't want to specify it)</small></h4>

        <?php
        foreach ($queryParams as $param) {
        ?>
            <?php
            if($param->param_key == 'excludeAirlines') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">Comma-separated list of airline codes to exclude (code type specified by the codeType query parameter). Cannot be combined with includeAirlines.</label>
                    <input type="text" name="excludeAirlines" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if($param->param_key == 'includeAirlines') {
            ?>
            <div class="fids_admin_page_input">
                <label for="">Comma-separated list of airline codes to include (code type specified by the codeType query parameter). Cannot be combined with excludeAirlines.</label>
                <input type="text" name="includeAirlines" value="<?php echo $param->param_val ?>">
            </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'includeCodeshares') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">Whether to include codeshares (multiple airlines sharing the same flight). Default is "true".</label>
                    <select name="includeCodeshares" id="">
                        <option <?php if($param->param_val == 1) { echo 'selected'; } ?> value="1">True/Yes</option>
                        <option <?php if($param->param_val == 0) { echo 'selected'; } ?> value="0">False/No</option>
                    </select>
                </div>
            <?php
            }
            ?>
            <?php
            if($param->param_key == 'terminal') {
            ?>
            <div class="fids_admin_page_input">
                <label for="">Terminal to filter results by. If specified, only flights departing from/arriving to the given terminal will be returned.</label>
                <input type="text" name="terminal" value="<?php echo $param->param_val ?>">
            </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'timeFormat') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">Format for time-based fields; either "12" or "24" for 12-hour or 24-hour format respectively.</label>
                    <select name="timeFormat" id="">
                        <option <?php if($param->param_val == 12) { echo 'selected'; } ?> value="12">12</option>
                        <option <?php if($param->param_val == 24) { echo 'selected'; } ?> value="24">24</option>
                    </select>
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'timeWindowBegin') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">The number of minutes before 'now' during which flights should potentially be included. Default window is based on airport classification.</label>
                    <input type="text" name="timeWindowBegin" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'timeWindowEnd') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">The number of minutes after 'now' during which flights should potentially be included. Default window is based on airport classification.</label>
                    <input type="text" name="timeWindowEnd" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'maxFlights') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">The maximum number of flights to be returned. If more than this number of flights are available within the time window they will be weighted according to relevance and only this many will be returned.</label>
                    <input type="text" name="maxFlights" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'lateMinutes') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">The number of minutes after which a flight should be considered late, when generating remarks. Defaults to 15.</label>
                    <input type="text" name="lateMinutes" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'boardingMinutes') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">The number of minutes before scheduled or estimated departure during which a flight should be considered boarding, when generating remarks. If not specified 'Boarding' messages will not be displayed.</label>
                    <input type="text" name="boardingMinutes" value="<?php echo $param->param_val ?>">
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'useRunwayTimes') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">Boolean value indicating whether runway times should be considered when determining delay and generating remarks. Defaults to 'false'.</label>
                    <select name="useRunwayTimes" id="">
                        <option <?php if($param->param_val == 0) { echo 'selected'; } ?> value="0">False/No</option>
                        <option <?php if($param->param_val == 1) { echo 'selected'; } ?> value="1">True/Yes</option>
                    </select>
                </div>
            <?php
            }
            ?>
            <?php
            if ($param->param_key == 'excludeCargoOnlyFlights') {
            ?>
                <div class="fids_admin_page_input">
                    <label for="">
                        Boolean value indicating whether cargo-only flights should be excluded. Defaults to 'false'.</label>
                    <select name="excludeCargoOnlyFlights" id="">
                        <option <?php if($param->param_val == 0) { echo 'selected'; } ?> value="0">False/No</option>
                        <option <?php if($param->param_val == 1) { echo 'selected'; } ?> value="1">True/Yes</option>
                    </select>
                </div>
            <?php
            } }
            ?>

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
                                    class="visibilityState"
                                    name="arrivalElements[]"
                                    value="<?php echo $element->api_key?>"
                                    <?php echo in_array($element->api_key, $arrivalElements) ? 'checked="checked"' : '' ?>
                                    type="checkbox">
                            <label for="<?php echo $element->api_key . '_arrival'?>">Show on <b>Arrival</b> section</label>
                        </div>
                        <div class="fids_admin_page_input fids_flex_row">
                            <input
                                    id="<?php echo $element->api_key . '_departure'?>"
                                    class="visibilityState"
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
    jQuery(function() {
        jQuery('#sortable').sortable({
            stop: function(event, ui) {
                const data = jQuery('#sortable').sortable('toArray', {
                    attribute: 'data-key'
                });
                console.log(data)
                jQuery('#orderInput').val(data)
            }
        });
        // jQuery('.visibilityState').change(function() {
        //     console.log('das')
        // })
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