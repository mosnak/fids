const reloadInterval = 2 // 60
const reloadCountMax = 5 // 30
let reloadCount = 0

jQuery('document').ready(function() {

    jQuery('body').on('click', '.fids-refresh-btn', function() {
        reloadCount = 0
        jQuery('.fids-refresh-btn').css('display', 'none');
    })

    setInterval(function() {
        if(reloadCount < reloadCountMax) {
            reloadCount++;
            jQuery.post(fids_client.ajax_url, {
                    'action': 'fids',
                    'airport': fidsAirport,
                    'type': fidsType,
                },
                function(response) {
                    jQuery('#fids').replaceWith(response)
                    if(reloadCount === reloadCountMax) {
                        jQuery('.fids-refresh-btn').css('display', 'block');
                    }
                }
            );
            if(reloadCount === reloadCountMax) {
                jQuery('.fids-refresh-btn').css('display', 'block');
            }
        }
    }, reloadInterval * 1000)
})