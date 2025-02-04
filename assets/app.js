import "./bootstrap.js";
import "bootstrap";
//import "bootstrap-icons";



import "./styles/app.css";
import $ from "jquery";
import "datatables.net";

//import "datatables.net-dt/css";
//import "datatables.net-dt/css/dataTables.min.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

function initDataTable() {
  const table = document.querySelector("#product");
  const tableOrders = document.querySelector("#orders");
  if (table && table.tagName === "TABLE") { // Ensure it's a <table>
    if ($.fn.DataTable.isDataTable("#product")) {
      $("#product").DataTable().destroy(); // Destroy existing DataTable instance
    }
    $("#product").DataTable({
      responsive: true,
      autoWidth: false,
      paging: true,
      searching: true,
      ordering: true,
    });
  } else {
    console.warn("DataTables initialization skipped: #product is not a table.");
  }



  if (!$.fn.DataTable.isDataTable('#orders')) {
    $('#orders').DataTable();
  }

  if (!$.fn.DataTable.isDataTable('#users')) {
    $('#users').DataTable();
  }


}

// Run on initial page load
document.addEventListener("DOMContentLoaded", function () {
  initDataTable();
});

// Run again when Turbo Drive loads a new page (if Symfony UX Turbo is enabled)
document.addEventListener("turbo:load", function () {
  initDataTable();
});


console.log("*******************************************");
