const reloadInterval = 10
jQuery('document').ready(function() {
    setInterval(function() {
        jQuery.post(fids_client.ajax_url, {
                'action': 'fids',
                'foobar_id':   123
            },
            function(response) {
                console.log('The server responded: ', response);
            }
        );
    }, reloadInterval * 1000)
})
