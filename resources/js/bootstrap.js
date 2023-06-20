window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery ;//= require('jquery');

    //require('bootstrap'); 
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
 
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

 

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');
//console.log(window.Laravel.access_token);
var token = window.Laravel.csrfToken;
var access_token = 'Bearer '+ window.Laravel.access_token;//eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWZhZjgzNmJmNmY1NDdkYjgzOGQyNzU4ZWM1MzBmYmFjMzU0ZTIxZjA2NjRhMGNmMzFjOGI2YzE0NTVlYmQyNTIyNjA3ZjNmOWIwNTI4YWYiLCJpYXQiOjE2MTI0MjM0ODksIm5iZiI6MTYxMjQyMzQ4OSwiZXhwIjoxNjQzOTU5NDg5LCJzdWIiOiI0Iiwic2NvcGVzIjpbXX0.QaluKodm6QzKcY-diSKl5g8xICWl-L0dB7MofkYnv6Ik4t4e1-_ZtijSPOQxpYUMhUxWtxeJHpxGsHjWey_z1wKUibLu5wtkENiGObCaTJ9XG9pMmPnRkU1xdWUIpHwUxI8j7pHMaIgr2lXD9nlEitVpJJDin7VeIEuH_9RnCSdqooI9GUnYYE3L39OWjb7O7fvORuj1yh2A2LhD1JQav4k6U8EPBSIggDa8B1o63F9Qsb-giHVb6tNh_-p3cB3XBNTLOTksYWnE0VdtzFNJZQ8jPzEHJmaUz5tWuCFJk3Ox8tpbZ-_8xciiq8CN-vYt2ybpt7JD2ukncK8IrVRD2GxLAamE3Yut9UFqHioayyFT8Vh-OPo3Rr-p0aIlJ9SVAb6zGtEnh-7PW5XwqJHQlg9CZwuXOJLhsmcdqN9FlmTkfh4U00IsGi4KKgNRujvPKYqniw0LNDMOz1cRygQg6N0RUrSGUFfrcTROJpttVfaP-zkWVOWKOXJ9315wiygOLO6ri1iLjl4nVs0g5ruatgIdq-8rdtu4JBm_2ZLNClsL2YplDOI8H5aToASzgNZRbYymYYK56RscUsN0tuhKOOsPQJPmX-hbf26_RGLEdtq9QybGqp9VEkhbr1JR-oJbRBoRcju5AKIAnitHUQ-Dau50wUW-7vAXSJOBmdca53I';


window.Echo = new Echo({
    broadcaster: 'pusher',
    key:process.env.MIX_PUSHER_APP_KEY,
    cluster:process.env.MIX_PUSHER_APP_CLUSTER,
    //authEndpoint: '/broadcasting/auth', //dedault
    forceTLS: true,
    auth:{
        headers:{
            'X-CSRF-Token':token,
            'Authorization':access_token
        }
    }
});

