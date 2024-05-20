@extends('layouts.app')
@section('tittle', 'Expertis | User')
@section('content')
<div class="page-wrapper">
   <!-- ============================================================== -->
   <!-- Bread crumb and right sidebar toggle -->
   <!-- ============================================================== -->
   <div class="page-breadcrumb">
       <div class="row">
           <div class="col-7 align-self-center">
               <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning Jason!</h3>
               <div class="d-flex align-items-center">
                   <nav aria-label="breadcrumb">
                       <ol class="breadcrumb m-0 p-0">
                           <li class="breadcrumb-item"><a href="index.html">User Profile</a>
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
       <!-- Start Sales Charts Section -->
       <!-- *************************************************************** -->
           <!-- multi-column ordering -->
           <div class="row">
               <div class="col-12">
                   <div class="card">
                       <div class="card-body">
                           <h4 class="card-title">Expenses Claim Record</h4>
                        <img style="width:100%; height:50%;" src="{{ asset('assets/img/bg04.jpg') }}" alt="">
                        <img src="{{ asset('assets/img/user-default.png') }}" alt="user" class="center">
                       </div>
                   </div>
               </div>
           </div>

   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->


 <!-- ============================================================== -->
 <!-- Soucre Modal  -->
 <!-- ============================================================== -->
@include('EC.add-ec-modal')
@include('EC.image-ec-modal')
@endsection

@section('script') 
 <script src="{{ asset('assets/js/ec.js') }}"></script>  
 @endsection