 <!-- Core CSS -->
 <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-semi-dark.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

 <!-- Vendors CSS -->
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css" />
 <!--Leaflet-->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin="" />
 <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
 <!-- Page CSS -->

 <style>
     .form-group {
         margin-bottom: 5px !important;
     }

     .swal2-container {
         z-index: 9999 !important;
     }

     .swal2-confirm {
         background-color: #1a6bd1 !important;
     }

     .noborder-form {
         width: 100%;
         border: 0px;
     }

     .noborder-form:focus {
         outline: none;
     }

     #tabelpelanggan_filter {
         margin-bottom: 10px;
     }

     #tabelharga_filter {
         margin-bottom: 10px;
     }

     .modal-backdrop {
         width: 100vw;
         /* Tutup seluruh lebar layar */
         height: 100vh;
         /* Tutup seluruh tinggi layar */
         position: fixed;
         top: 0;
         left: 0;
     }

     /*=========================================================================================
 File Name: customizer.scss
 Description: CSS used for demo purpose only. Remove this css from your project.
 ----------------------------------------------------------------------------------------
 Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
 Author: PIXINVENT
 Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
     .customizer {
         width: 400px;
         right: -400px;
         padding: 0;
         background-color: #fff;
         z-index: 9999;
         position: fixed;
         top: 0;
         bottom: 0;
         height: 100vh;
         height: calc(var(--vh, 1vh) * 100);
         transition: right 0.4s cubic-bezier(0.05, 0.74, 0.2, 0.99);
         backface-visibility: hidden;
         border-left: 1px solid rgba(0, 0, 0, 0.05);
         box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.11),
             0 5px 15px 0 rgba(0, 0, 0, 0.08);
     }

     .customizer.open {
         right: 0;
     }

     .customizer .customizer-content {
         position: relative;
         height: 100%;
     }

     .customizer .customizer-close {
         position: absolute;
         right: 30px;
         top: 20px;
         padding: 7px;
         width: auto;
         z-index: 10;
         color: #626262;
     }

     .customizer .customizer-close i {
         font-size: 1.71rem;
     }

     .customizer .customizer-toggle {
         background: #7367f0;
         color: #fff;
         display: block;
         box-shadow: -3px 0px 8px rgba(0, 0, 0, 0.1);
         border-top-left-radius: 6px;
         border-bottom-left-radius: 6px;
         position: absolute;
         top: 50%;
         width: 38px;
         height: 38px;
         left: -39px;
         text-align: center;
         line-height: 40px;
         cursor: pointer;
     }

     .customizer .color-box {
         height: 35px;
         width: 35px;
         margin: 0.5rem;
         border-radius: 0.5rem;
         cursor: pointer;
     }

     .customizer .color-box.selected {
         box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.5);
     }
 </style>
