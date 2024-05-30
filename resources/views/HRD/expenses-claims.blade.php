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
                           <li class="breadcrumb-item"><a href="index.html">Expenses Claim</a>
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
                        <div class="row">
                            <div class="col-md-4 ">
                                <label>Filter Tanggal</label>
                                  <input type="text" id="date-ec" data-config="" class="form-control" width="100%">
                            </div>
                            <div class="col-md-2">
                             <div class="form-group mt-4">
                                 <select class="form-select-sm" name="unit_bisnis" id="unit-bisnis-ec" data-placeholder="Select Unit Bisnis" >
                                   </select>
                                   <div class="invalid-feedback"></div>
                             </div>  
                            </div>
                            <div class="col-md-3">
                             <div class="form-group mt-4">
                                 <select class="form-select-sm" name="department" id="department-ec" data-placeholder="Select Department" >
                                   </select>
                                   <div class="invalid-feedback"></div>
                             </div> 
                            </div>
                            <div class="col-md-2">
                             <div class="form-group mt-4">
                                 <select class="form-select-sm" name="employee" id="employee-ec" data-placeholder="Select Employee" >
                                   </select>
                                   <div class="invalid-feedback"></div>
                             </div>  
                            </div>
                        
                        </div>
                        <div class="row ">
                         <div class="col-md-3 d-flex justify-content-between">
                             {{-- <button type="button" class="btn btn-info mt-4 text-right validate-all" onclick="checkAll()" >Validasi All</button>  --}}
                             <a  href="{{ url('ec/export-excel-hrd') }}" id="export-excel-ec" class="btn btn-success mt-4 text-right validate-all" >Export Excel <i class="bi bi-file-earmark-spreadsheet-fill"></i></a> 
                         </div>
 
                        </div>
                           <h4 class="card-title mt-4">Expenses Claim Record</h4>
                           <div class="table-responsive">
                               <table id="datatable-ec"
                                   class="table border table-striped table-bordered text-nowrap" style="width:100%">
                                   <thead>
                                       <tr>
                                        <th class="check-all"></th>
                                           <th>No</th>
                                           <th>Day</th>
                                           <th>Date</th>
                                           <th >Name</th>
                                           <th >Department</th>
                                           <th>Meal Allowance</th>
                                           <th>Transportation</th>
                                           <th>Parking & Tol</th>
                                           <th>Overtime</th>
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


 <!-- ============================================================== -->
 <!-- Soucre Modal  -->
 <!-- ============================================================== -->
@include('EC.add-ec-modal')
@include('EC.image-ec-modal')
@endsection

@section('script') 
 <script src="{{ asset('assets/js/hrd.js') }}"></script>  
 @endsection