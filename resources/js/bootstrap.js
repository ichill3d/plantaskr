import $ from 'jquery';
window.$ = window.jQuery = $;
console.log('jQuery is loaded:', typeof window.$ !== 'undefined');

import axios from 'axios';

import DataTable from 'datatables.net-dt';

window.axios = axios;

// Debug to ensure jQuery is loaded


// Attach DataTables to the global jQuery object
$.fn.dataTable = DataTable;

// Default Axios headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Debug to check Axios setup
console.log('Axios is loaded:', typeof window.axios !== 'undefined');

// Add CSRF token to Axios headers
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    console.log('CSRF token is set:', token.content);
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf');
}

// Optional: Add global error handling for Axios
window.axios.interceptors.response.use(
    response => response,
    error => {
        console.error('Axios error:', error);
        return Promise.reject(error);
    }
);



// Debug to confirm bootstrap.js has executed
console.log('bootstrap.js executed successfully');
