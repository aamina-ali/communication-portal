import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || document.querySelector('meta[name="reverb-key"]')?.getAttribute('content');
const reverbHost = import.meta.env.VITE_REVERB_HOST || document.querySelector('meta[name="reverb-host"]')?.getAttribute('content');
const reverbPort = import.meta.env.VITE_REVERB_PORT || document.querySelector('meta[name="reverb-port"]')?.getAttribute('content');
const reverbScheme = import.meta.env.VITE_REVERB_SCHEME || document.querySelector('meta[name="reverb-scheme"]')?.getAttribute('content') || 'https';

if (reverbKey) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: reverbHost,
        wsPort: reverbPort ? parseInt(reverbPort) : 80,
        wssPort: reverbPort ? parseInt(reverbPort) : 443,
        forceTLS: reverbScheme === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} else {
    console.warn('Reverb app key is not defined. Real-time broadcasting is disabled.');
}
