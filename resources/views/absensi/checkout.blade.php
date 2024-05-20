@extends('layouts.app')
@section('tittle', 'Expertis | Absensi')
@section('content')
<div class="page-wrapper">
   <!-- ============================================================== -->
   <!-- Bread crumb and right sidebar toggle -->
   <!-- ============================================================== -->
   <div class="page-breadcrumb">
       <div class="row">
           <div class="col-7 align-self-center">
               <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Scan Barcode</h3>
               <div class="d-flex align-items-center">
                   <nav aria-label="breadcrumb">
                       <ol class="breadcrumb m-0 p-0">
                           <li class="breadcrumb-item"><a href="index.html">Checkout</a>
                           </li>
                       </ol>
                   </nav>
               </div>
           </div>

       </div>
   </div>
   <!-- ============================================================== -->
   <!-- End Bread crumb and right sidebar toggle -->
   <!-- ============================================================== -->
   <!-- ============================================================== -->
   <!-- Container fluid  -->
   <!-- ============================================================== -->
   <div class="container-fluid">
       <!-- *************************************************************** -->
       <!-- Start First Cards -->
       <!-- *************************************************************** -->
       <div id="reader" class="visiting_id" width="600px"></div>

      <!-- *************************************************************** -->
       <!-- Start First Cards -->
       <!-- *************************************************************** -->

   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->

@endsection

@section('script')
 <script src="{{ asset('assets/plugins/qr_code.js') }}"></script>  
 <script src="{{ asset('assets/js/scan-barcode-checkout.js') }}"></script>  
 @endsection