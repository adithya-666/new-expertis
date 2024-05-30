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
               <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">{{ $departement }}</h3>
               <div class="d-flex align-items-center">
                   <nav aria-label="breadcrumb">
                       <ol class="breadcrumb m-0 p-0">
                           <li class="breadcrumb-item"><a href="index.html">Presence</a>
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
       <div class="row">
           <div class="col-sm-6 col-lg-3">
               <div class="card border-end">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div >
                               <div class="d-inline-flex align-items-center checkin">
                                   {{-- <h2 class="text-dark mb-1 font-weight-medium click-checkin"></h2> --}}
                               </div>
                               <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Check In
                               </h6>
                           </div>
                           <div class="ms-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-sm-6 col-lg-3">
               <div class="card border-end ">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div>
                               <div class="d-inline-flex align-items-center checkout">
                                   {{-- <span class="badge bg-success font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none"><i class="bi bi-check"></i></span> --}}
                               </div>
                               <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Check Out
                               </h6>
                           </div>
                           <div class="ms-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-sm-6 col-lg-3">
               <div class="card border-end ">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div >
                               <div class="d-inline-flex align-items-center start-overtime">
                                  
                               </div>
                               <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Overtime
                               </h6>
                           </div>
                           <div class="ms-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-muted"><a href="{{ url('absensi/overtime') }}" class="badge bg-info font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">Detail</a></span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col-sm-6 col-lg-3">
               <div class="card ">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div>
                               <div class="d-inline-flex align-items-center end-overtime">
                                  
                               </div>
                               <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">End Overtime</h6>
                           </div>
                           <div class="ms-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-muted"></span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <!-- *************************************************************** -->
       <!-- End First Cards -->
       <!-- *************************************************************** -->
       <!-- *************************************************************** -->
       <!-- Start Sales Charts Section -->
       <!-- *************************************************************** -->
           <!-- multi-column ordering -->
           <div class="row">
               <div class="col-12">
                   <div class="card">
                       <div class="card-body">
                       <div class="row">
                           <div class="col-md-4 ">
                               <label>Filter Tanggal</label>
                                 <input type="text" id="date-absensi" data-config="" class="form-control" width="100%">
                           </div>
                           <div class="col-md-2">
                            <div class="form-group mt-4">
                                <select class="form-select-sm" name="unit_bisnis" id="unit-bisnis" data-placeholder="Select Unit Bisnis" >
                                  </select>
                                  <div class="invalid-feedback"></div>
                            </div>  
                           </div>
                           <div class="col-md-3">
                            <div class="form-group mt-4">
                                <select class="form-select-sm" name="department" id="department" data-placeholder="Select Department" >
                                  </select>
                                  <div class="invalid-feedback"></div>
                            </div> 
                           </div>
                           <div class="col-md-2">
                            <div class="form-group mt-4">
                                <select class="form-select-sm" name="employee" id="employee" data-placeholder="Select Employee" >
                                  </select>
                                  <div class="invalid-feedback"></div>
                            </div>  
                           </div>
                       
                       </div>
                       <div class="row ">
                        <div class="col-md-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-info mt-4 text-right validate-all" onclick="checkAll()" >Validasi All</button> 
                            <a  href="{{ url('absensi/export-excel-hrd') }}" id="export-excel" class="btn btn-success mt-4 text-right validate-all" >Export Excel <i class="bi bi-file-earmark-spreadsheet-fill"></i></a> 
                        </div>

                       </div>
                           <h4 class="card-title mt-4">Presence Record</h4>
                           <div class="table-responsive">
                               <table id="absensi-datatable-hrd"
                                   class="table border table-striped table-bordered text-nowrap" style="width:100%">
                                   <thead>
                                       <tr> 
                                           <th class="check-all"></th>
                                           <th>No</th>
                                           <th>Day</th>
                                           <th>Date</th>   
                                           <th>Name</th>
                                           <th>Departement</th>
                                           <th>Check In</th>
                                           <th>Check Out</th>
                                           <th>Overdue</th>
                                           <th>Working Hour</th>
                                           <th>Type of Presence</th>     
                                           <th>Status</th>    
                                           <th>Action</th>
                                       </tr>
                                   </thead>
                                   <tbody></tbody>
                               </table>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->


@include('absensi.checkin-modal')
@include('absensi.checkout-modal')
@include('absensi.overtime-modal')
@endsection

@section('script') 
 <script src="{{ asset('assets/js/hrd.js') }}"></script>  
 @endsection