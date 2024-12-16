import './bootstrap';
import '/node_modules/datatables.net-dt/css/dataTables.dataTables.min.css';
import flatpickr from "flatpickr";
document.addEventListener('DOMContentLoaded', () => {
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d", // Matches your database format
        allowInput: false, // Disables manual typing
    });
});
