import './bootstrap';
import '/node_modules/datatables.net-dt/css/dataTables.dataTables.min.css';
import flatpickr from "flatpickr";
import Quill from 'quill'; // Core import for Quill 2
import Snow from 'quill/themes/snow'; // Import Snow theme
import Toolbar from 'quill/modules/toolbar'; // Import Toolbar module

import 'quill/dist/quill.snow.css'; // Import Snow theme CSS



window.Quill = Quill; // Make Quill globally available for debugging
document.addEventListener('DOMContentLoaded', () => {
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d", // Matches your database format
        allowInput: false, // Disables manual typing
    });
});
