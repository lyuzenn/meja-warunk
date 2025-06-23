import _ from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,

    // Konfigurasi tambahan untuk memastikan koneksi yang stabil
    wsHost: `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wssHost: `wss-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: 80,
    wssPort: 443,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
