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
               <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Good Morning Jason!</h3>
               <div class="d-flex align-items-center">
                   <nav aria-label="breadcrumb">
                       <ol class="breadcrumb m-0 p-0">
                           <li class="breadcrumb-item"><a href="index.html">Allowance Page</a>
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
 
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Business Trip Allowance Form</h4>
                  <hr>
                        <form action="{{ url('bt/create-bt') }}" method="POST" id="formCreateBT">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                <div class="col-lg-6">
                                    <h3>Personal</h3>
                                    <label class="form-label">Commander <span class="text-danger">*</span> </label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control" name="commander" value="Achmad Taufiq"
                                                    placeholder="Commander" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label">Executor <span class="text-danger">*</span> </label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control"
                                                    placeholder="Executor" name="executor"  value="{{ $employee_name }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label">Departure <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <select class="form-select" name="departure" id="departure" data-placeholder="Select Departure" style="width: 100%">
                          
                                                  </select>
                                                  <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label">Destination <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                             <select class="form-select" name="destination[]" id="destination" data-placeholder="Select Destination" style="width: 100%" multiple>

                                                  </select>
                                                  <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-6">
                                <h3>Estimated Trip Cost</h3>
                                <label class="form-label">Days <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <input type="number" min="1" class="form-control" id="day" name="day"
                                                placeholder="Days">
                                                <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-label">Date of Departure <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <input type="text"  class="form-control"
                                                placeholder="Date of Departure" id="date" name="date">
                                                <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <label class="form-label">Transportation <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1" name="transportation"
                                                class="custom-control-input" value="mobil" checked>
                                            <label class="custom-control-label" for="customRadio1">Mobil</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2" name="transportation"
                                                class="custom-control-input" value="motor" >
                                            <label class="custom-control-label" for="customRadio2">Motor</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio3" name="transportation"
                                                class="custom-control-input" value="umum" >
                                            <label class="custom-control-label" for="customRadio3">Kendaraan Umum</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio4" name="transportation"
                                                class="custom-control-input" value="Join Visiting" >
                                            <label class="custom-control-label" for="customRadio4">Gabung dengan rekan</label>
                                        </div>
                                    </div>
                                </div>
                                {{-- <label class="form-label">Too Expenses</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio1" name="expenses"
                                                class="custom-control-input" value="true">
                                            <label class="custom-control-label" for="customRadio1">Reserve (20%)</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customRadio2" name="expenses"
                                                class="custom-control-input" value="false" checked>
                                            <label class="custom-control-label" for="customRadio2">No</label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" name="deskripsi" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 70px"></textarea>
                                            <label for="floatingTextarea2">Description</label>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                            <div class="form-actions mt-4">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    <button type="reset" class="btn btn-dark">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
          
    

   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->



@endsection

@section('script') 
<script src="{{ asset('assets/js/bt.js') }}"></script>  
 @endsection