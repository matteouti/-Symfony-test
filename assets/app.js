import "./bootstrap.js";
import "bootstrap";
//import "bootstrap-icons";

import "./styles/app.css";
import $ from "jquery";
import "datatables.net";

//import "datatables.net-dt/css";
//import "datatables.net-dt/css/dataTables.min.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

document.addEventListener("DOMContentLoaded", function () {
  const table = document.querySelector("#product"); // Remplacez par l'ID de votre table
  if (table) {
    $(table).DataTable();
  }
});

console.log("*******************************************");
