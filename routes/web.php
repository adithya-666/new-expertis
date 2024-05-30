<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ECController;
use App\Http\Controllers\BTController;
use App\Http\Controllers\userController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    // Process Login
  Route::get('/', [LoginController::class, 'index'])->name('login');
  Route::post('/login', [LoginController::class, 'login']);
  
  Route::view('/error-page', 'error')->name('error.page');

  Route::get('/generate', function(){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
 });
  
  });


  Route::middleware(['auth', 'session.timeout'])->group(function () {


    route::prefix('user')->group(function(){
      Route::get('/', [userController::class, 'index']);
     });

    // ABSENSI KARYAWAN : check in, check out DLL
    route::prefix('absensi')->group(function(){

      Route::get('/', [AbsensiController::class, 'index']);
      Route::get('/ip', [AbsensiController::class, 'ip']);
      Route::get('/check-location', [AbsensiController::class, 'CheckLocation']);
      Route::get('/check-in-barcode/{id}', [AbsensiController::class, 'CheckinBarcode']);
      Route::get('/check-check-in', [AbsensiController::class, 'CheckCheckIn']);
      Route::post('/process-scan-barcode-checkin', [AbsensiController::class, 'processScanBarcodeCheckIn']);
      Route::get('/checkout', [AbsensiController::class, 'CheckOut']);
      Route::get('/check-out-barcode', [AbsensiController::class, 'CheckOutBarcode']);
      Route::post('/process-scan-barcode-checkout', [AbsensiController::class, 'processScanBarcodeCheckOut']);
      Route::get('/check-check-out', [AbsensiController::class, 'CheckCheckOut']);
      Route::put('/bt-update/{presence_id}', [AbsensiController::class, 'BTUpdate']);
      Route::get('/datatable-absensi/{startDate}/{endDate}', [AbsensiController::class, 'datatable']);
      Route::get('/overtime', [AbsensiController::class, 'overtime']);
      Route::get('/datatable-overtime', [AbsensiController::class, 'datatableOvertime']);
      Route::get('/check-ot', [AbsensiController::class, 'checkOt']);
      Route::post('/add-ot', [AbsensiController::class, 'createOT']);
      Route::get('/detail-ot-start-time', [AbsensiController::class, 'detailOTStartTime']);
      Route::get('/detail-ot-end-time', [AbsensiController::class, 'detailOTEndTime']);
      Route::put('/end-overtime/{id}', [AbsensiController::class, 'endOverTime']);
      Route::put('/update-status-validasi-all', [AbsensiController::class, 'updateStatusAbsensiAll']);

      // ROLE Manager
      Route::get('presence-manager', [AbsensiController::class, 'presenceManager']);
      Route::get('/datatable-absensi-manager/{startDate}/{endDate}', [AbsensiController::class, 'datatableAbsensiManager']);
   
      // ROLE HRD
      Route::get('presence-hrd', [AbsensiController::class, 'presenceHRD']);
      Route::get('/datatable-absensi-hrd/{startDate?}/{endDate?}/{employee?}/{department?}/{unit_bisnis?}', [AbsensiController::class, 'datatableAbsensiHRD']);
      Route::get('/export-excel-hrd/{startDate?}/{endDate?}/{employee?}/{department?}/{unit_bisnis?}', [AbsensiController::class, 'exportExcel']);
      Route::get('/select-employee', [AbsensiController::class, 'selectEmployee']);
      Route::get('/select-department', [AbsensiController::class, 'selectDepartment']);
      Route::get('/select-unit-bisnis', [AbsensiController::class, 'selectUnitBisnis']);
      Route::put('/update-status-absensi/{id}/{status}', [AbsensiController::class, 'updateStatusAbsensi']);
    });

    // Expenses Claim : data input nota bensin , transportasi, tol dan uang makan
    route::prefix('ec')->group(function(){
      Route::get('/', [ECController::class, 'index']);
      Route::get('/datatable/{startDate}/{endDate}', [ECController::class, 'datatable']);
      Route::get('/check-ec', [ECController::class, 'checkEC']);
      Route::post('/add-ec', [ECController::class, 'addEC']);

      // ROLE Manager 
      Route::get('/expenses-claims-manager', [ECController::class, 'expensesClaimsManager']);
      Route::get('/datatable-explains-claims-manager/{startDate}/{endDate}', [ECController::class, 'datatableExplainsClaimsManager']);
      Route::put('/update-status-ec/{id}/{status}', [ECController::class, 'updateStatusEC']);
      Route::put('/update-status-transportasi-manager/{id}', [ECController::class, 'updateStatusECTransportasi']);
    
    
      // ROLE HRD 
      Route::get('/expenses-claims-hrd', [ECController::class, 'expensesClaimHRD']);
      Route::get('/datatable-explains-claims-hrd/{startDate?}/{endDate?}/{employee?}/{department?}/{unit_bisnis?}', [ECController::class, 'datatableExplainsClaimHRD']);
      Route::put('/update-status-ec-hrd/{id}/{status}', [ECController::class, 'updateStatusEcHRD']);
      Route::get('/select-employee-ec', [ECController::class, 'selectEmployee']);
      Route::get('/select-department-ec', [ECController::class, 'selectDepartment']);
      Route::get('/select-unit-bisnis-ec', [ECController::class, 'selectUnitBisnis']);
      Route::get('/export-excel-hrd/{startDate?}/{endDate?}/{employee?}/{department?}/{unit_bisnis?}', [ECController::class, 'exportExcel']);
     });


    route::prefix('bt')->group(function(){
      Route::get('/', [BTController::class, 'index']);
      Route::get('/add-allowance-bt', [BTController::class, 'addAllowanceBT']);
      Route::post('/create-bt', [BTController::class, 'createBT']);
      Route::get('/select-colleague', [BTController::class, 'selectColleague']);
      Route::get('/select-departure', [BTController::class, 'selectDeparture']);
      Route::get('/select-destination/{departureId}', [BTController::class, 'selectDestination']);
      Route::get('/datatable-bt', [BTController::class, 'datatable']);
     });

    Route::post('/logout', [LoginController::class, 'logout']);
      
  });
