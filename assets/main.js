const reloadInterval = 2 // 60
const reloadCountMax = 5 // 30
let reloadCount = 0

jQuery('document').ready(function() {

    jQuery('.fids-refresh-btn').on('click', function() {
        reloadCount = 0
        jQuery('.fids-refresh-btn').css('display', 'none');
    })

    setInterval(function() {
        if(reloadCount < reloadCountMax) {
            reloadCount++;
            jQuery.post(fids_client.ajax_url, {
                    'action': 'fids',
                    'foobar_id':   123
                },
                function(response) {
                    console.log('The server responded: ', response);
                }
            );
            if(reloadCount === reloadCountMax) {
                jQuery('.fids-refresh-btn').css('display', 'block');
            }
        }
    }, reloadInterval * 1000)
})


// TODO implemet button