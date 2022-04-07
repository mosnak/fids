const reloadInterval = 10 // one min
const reloadCountMax = 3 // 30 times
let reloadCount = 0

jQuery('document').ready(function() {

    jQuery('body').on('click', '.fids-refresh-btn', function() {
        reloadCount = 0
        jQuery('.fids-refresh-btn').css('display', 'none');
    })

    setInterval(function() {
        console.log('interval')
        if(reloadCount < reloadCountMax) {
            reloadCount++;
            if(fidsAirport_departures != undefined){
                jQuery.post(fids_client.ajax_url, {
                        'action': 'fids',
                        'airport': fidsAirport_departures,
                        'type': 'departures',
                    },
                    function(response) {
                        jQuery('#fids_departures').replaceWith(response)
                        if(reloadCount === reloadCountMax) {
                            jQuery('.fids-refresh-btn').css('display', 'block');
                        }
                    }
                );
            }
            if(fidsAirport_arrivals != undefined){
                jQuery.post(fids_client.ajax_url, {
                        'action': 'fids',
                        'airport': fidsAirport_arrivals,
                        'type': 'arrivals',
                    },
                    function(response) {
                        jQuery('#fids_arrivals').replaceWith(response)
                        if(reloadCount === reloadCountMax) {
                            jQuery('.fids-refresh-btn').css('display', 'block');
                        }
                    }
                );
            }

            if(reloadCount === reloadCountMax) {
                jQuery('.fids-refresh-btn').css('display', 'block');
            }
        }
    }, reloadInterval * 1000)
})