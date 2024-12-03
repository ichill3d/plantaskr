import axios from 'axios';
import $ from 'jquery';

window.axios = axios;
window.$ = window.jQuery = $;

// Default Axios headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token to Axios headers
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf');
}

// Optional: Set Axios base URL
window.axios.defaults.baseURL = `${process.env.MIX_APP_URL || ''}/`;

// Optional: Add global error handling for Axios
window.axios.interceptors.response.use(
    response => response,
    error => {
        console.error('Axios error:', error);
        return Promise.reject(error);
    }
);
