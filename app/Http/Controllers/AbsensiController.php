<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\presence;
use App\Models\User;
use App\Models\overtime;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Milon\Barcode\BarcodeGenerator;


class AbsensiController extends Controller
{
  public function index()
  {  

    $user_id  =  Auth::user()->id;

    $employee =  User::employee($user_id);

    $jabatan = $employee->jabatan;


    return view('absensi.absensi', compact('jabatan'));
  }

  
  public function ip()
  {
      return view('absensi.ip');
  }


  public function CheckLocation(Request $request)
  {

    $latitude = -6.9630887;
    $longitude = 107.5935687;

    // -7.8111081, 110.3425015

    $user_id  =  Auth::user()->id;

    $employee =  User::employee($user_id);

    $location = DB::table('barcode')->where('location', $employee->cabang)->first();

    $latitude_office =   $location->latitude; 
    $longitude_office = $location->longitude;
  
    $distance = $this->haversineDistance($latitude, $longitude, $latitude_office, $longitude_office);

    $user_location = intval($distance);

    // set radius from point location user to office
    $radius = 75;

   
    if ($user_location <=  $radius ) {

      $user_id = Auth::user()->id;

    return response()->json(['area' => true, 'user_id' => $user_id]);
  } else {
    return response()->json(['area' => false]);
  }
  }


  /**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
  private function haversineDistance($latitude, $longitude, $latitude_office, $longitude_office) {
    $earthRadius = 6371000; // Radius bumi dalam meter

    // $latDifference = deg2rad($latitude2 - $latitude1);
    // $lonDifference = deg2rad($longitude2 - $longitude1);

    // $a = sin($latDifference / 2) * sin($latDifference / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($lonDifference / 2) * sin($lonDifference / 2);
    // $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // $distance = $earthRadius * $c;

      // convert from degrees to radians
  $latFrom = deg2rad($latitude);
  $lonFrom = deg2rad($longitude);
  $latTo = deg2rad($latitude_office);
  $lonTo = deg2rad($longitude_office);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));


  return $angle * $earthRadius;


}


public function CheckinBarcode($id)
{
return view('absensi.checkin');
}

public function processScanBarcodeCheckIn(Request $request)
{
 try {

  $qr_code = $request->qr_code;
  $typeWork = $request->typeWork;


  $checkBarcode =  DB::table('barcode')->where('qr_code', $qr_code)->exists();

  // Check Type Presensi 
  if($typeWork == 'Kerja'){
 // Jika Type Work nya Kerja lalu cek qr code
    if($checkBarcode){

      $checkin_user = Carbon::now()->format('H:i:s');

      $day = Carbon::now()->translatedFormat('l');

      $checkin_time = DB::table('work_time')->where('hari', $day)->first();

      $currentCheckIn = $checkin_time->jam_masuk;

      $date = Carbon::today()->format('Y-m-d');

      if($checkin_user  <=  $currentCheckIn ){
        $totalDurationLate = 0;
      }else{
        $time_office = Carbon::parse($currentCheckIn);
        $time_late_user = Carbon::parse($checkin_user);

        $totalDurationLate = $time_office->diffInMinutes($time_late_user);
        // dd($checkin_user);
      }

    presence::create([
      'pegawai_id' => Auth::user()->id,
      'jam_masuk' => $checkin_user,
      'keterlambatan' => $totalDurationLate,
      'hari' => $day,
      'status' => 1,
      'jenis_presensi' => $typeWork,
      'tanggal' => $date
    ]);

    return response()->json(['message' => 'Successfully Checkin Barcode', 'status' => 200]);
  } 
// Jika barcode tidak ada di table barcode
  return response()->json(['message' => 'Failed, Barcode No Match', 'status' => 404]);


    } else {

      $checkin_user = Carbon::now()->format('H:i:s');

      $day = Carbon::now()->translatedFormat('l');

      $date = Carbon::today()->format('Y-m-d');

      $checkin_time = DB::table('work_time')->where('hari', $day)->first();

      $currentCheckIn = $checkin_time->jam_masuk;
  
      if($checkin_user  <=  $checkin_time->jam_masuk ){
        $totalDurationLate = 0;
      }else{
        $time_office = Carbon::parse($currentCheckIn);
        $time_late_user = Carbon::parse($checkin_user);

        $totalDurationLate = $time_office->diffInMinutes($time_late_user);
      }

    presence::create([
      'pegawai_id' => Auth::user()->id,
      'jam_masuk' => $checkin_user,
      'keterlambatan' => $totalDurationLate,
      'hari' => $day,
      'status' => 1,
      'jenis_presensi' => $typeWork,
      'tanggal' => $date
    ]);
    
    return response()->json(['message' => 'Successfully Checkin BT', 'status' => 200]);
  
    }
  
 } catch (\Exception $e) {
   return response()->json(['message' => $e->getMessage(),500]);
 }
}


public function CheckCheckIn()
{
  try {
    $user_id  =  Auth::user()->id;

    $date = Carbon::today()->format('Y-m-d');

    $jam_masuk = DB::table('presences')
    ->where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->first();

    if($jam_masuk){
      return response()->json(['data' => $jam_masuk, 'status' => true]);
    }else{
      return response()->json(['status' => false]);
    }
  } catch (\Exception $e) {
    return response()->json(['message' => $e->getMessage(),500]);
  }
}

public function CheckOut()
{
  try{

    $user_id  =  Auth::user()->id;

    $date = Carbon::today()->format('Y-m-d');

    $data = DB::table('presences')
                    ->where('pegawai_id', $user_id)
                    ->where('tanggal', $date)
                    ->orderBy('id', 'DESC')
                    ->first();


     if ($data->jenis_presensi == 'BT') {
      return response()->json([201, 'message' => 'Type BT Cant Checkout']);
     }               

    if(empty($data) ||  $data->status == 2){
      throw new \Exception('Sudah Checkout atau data absensi hari ini belum ada');
    }

    $day = Carbon::now()->translatedFormat('l');

    $checkout_time = DB::table('work_time')->where('hari', $day)->first();

    $currentCheckOut = $checkout_time->jam_keluar;

    $checkout_user = Carbon::now()->format('H:i:s');

    if($checkout_user  <=   $currentCheckOut ){
      return response()->json([300, 'message' => 'CheckOut Or BT', 'presence_id' => $data->id]);
    } else {
      
      return response()->json([200, 'message' => 'Check Valid']);
    }

    

  } catch (\Exception $e) {
    return response()->json(['message' => $e->getMessage(),500]);
  }
}

public function CheckOutBarcode()
{
  return view('absensi.checkout');
}

public function processScanBarcodeCheckOut()
{
  try{
    DB::beginTransaction();

    $user_id  =  Auth::user()->id;

    $date = Carbon::today()->format('Y-m-d');

    $data = DB::table('presences')
                    ->where('pegawai_id', $user_id)
                    ->where('tanggal', $date)
                    ->orderBy('id', 'DESC')
                    ->first();

    $checkout_user = Carbon::now()->format('H:i:s');

    $jam_masuk = Carbon::parse($data->jam_masuk);
    $jam_keluar = Carbon::parse($checkout_user);

    $totalDurationWork = $jam_masuk->diffInMinutes($jam_keluar);

    // Uptdate Checkout dan jam kerja
$presence = presence::where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->update([
      'jam_keluar' => $checkout_user,
      'jam_kerja' => $totalDurationWork,
      'status' => 2
    ]);

    // Ambil data absensi pegawai
$presence = presence::where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->first();

    $day = Carbon::now()->translatedFormat('l');

    // check jika pegawai terlambat
    if($presence->keterlambatan == 0){
      // Jika tidak terlambat maka beri uang makan sesuai dengan golongan pegawai
      $golongan = DB::table('employees')->select('employees.user_id','employees.golongan' ,'golongan_pegawai.uang_makan')->leftJoin('golongan_pegawai', 'employees.golongan', 'golongan_pegawai.golongan')->where('user_id', $user_id)->first();

      $uang_makan = $golongan->uang_makan;
    } else {
      // Jika kesiangan maka beri uang makan nya 0
      $uang_makan = 0;
    }

    DB::table('expenses_claims')
    ->insert([
      'presensi_id' => $presence->id,
      'pegawai_id' => $user_id ,
      'hari' => $day,
      'tanggal' => $date,
      'uang_makan' => $uang_makan
    ]);

    DB::commit();
    
    return response()->json([ 'status' => 200, 'message' => 'Successfully CheckOut']);
  } catch (\Exception $e) {
    DB::rollBack();
    return response()->json(['message' => $e->getMessage(),500]);
  }
}

public function CheckCheckOut()
{
  try {
    $user_id  =  Auth::user()->id;
    $date = Carbon::today()->format('Y-m-d');
    
    $jam_keluar =DB::table('presences')
    ->where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->first();


    if($jam_keluar->jam_keluar !== null){
      return response()->json(['data' => $jam_keluar, 'status' => true]);
    }else{
      return response()->json(['status' => false]);
    }
  } catch (\Exception $e) {
    return response()->json(['message' => $e->getMessage(),500]);
  }
}


public function BTUpdate($presence_id)
{
    DB::table('presences')->where('id', $presence_id)->update(['jenis_presensi' => 'BT']);

    return response()->json(['message' => 'Successfully BT Update', 'status' => 200]);
}


public function datatable($startDate = null, $endDate = null)
{
  $user_id  =  Auth::user()->id;

  $employee =  User::employee($user_id);

 $data =  DB::table('presences')->select('presences.*', 'employees.nama_pegawai');
 $data->leftJoin('employees','employees.id', 'presences.pegawai_id');

 if($employee->jabatan == 'Manager'){
  
  if ($startDate == 'null' && $endDate == 'null') {
    $from = Carbon::today();
    $to = Carbon::today();
} else {
    $from = Carbon::parse($startDate);
    $to = Carbon::parse($endDate);
}


  $data->where('departemen', $employee->departemen);
  $data->where('tanggal', '>=' ,$from);
  $data->where('tanggal', '<=' ,$to);
 }else {
  $data->where('pegawai_id', $user_id );
 }

 $data->get();

 return DataTables::of($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);

}

public function overtime()
{
  return view('absensi.ot');
}

public function datatableOvertime()
{
  try{
 
    $data = overtime::where('pegawai_id', Auth::user()->id)->get();

    return DataTables::of($data)
    ->addIndexColumn()
    ->escapeColumns([])
    ->make(true);
  }catch (\Exception $e) {
    return response()->json(['message' => $e->getMessage(),500]);
  }
}

public function checkOt()
{
  try {
    $user_id  =  Auth::user()->id;
    $date = Carbon::today()->format('Y-m-d');
    
    $data =DB::table('presences')
    ->where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->first();

    if(empty($data)){
      return response()->json(['message' => 'Data Presences Not Found', 'status' => 404]);
    }else if($data->status == 1){
      return response()->json(['message' => 'You have not Check Out', 'status' => 500]);
    }

    
    return response()->json(['message' => 'Data Found', 'status' => 200]);
  } catch (\Exception $e) {
    return response()->json(['message' => $e->getMessage(),500]);
  }
}


public function detailOTStartTime()
{
  $user_id  =  Auth::user()->id;
  $date = Carbon::today()->format('Y-m-d');
  
  $data = DB::table('overtimes')
  ->where('pegawai_id', $user_id)
  ->where('date', $date)
  ->orderBy('id', 'DESC')
  ->first();

  if(empty($data)){
    return response()->json(['message' => 'Data Not Found', 'status' => false]);
  }else{
    return response()->json(['data' => $data, 'status' => true]);
  }
}

public function detailOTEndTime()
{
  $user_id  =  Auth::user()->id;
  $date = Carbon::today()->format('Y-m-d');
  
  $data = DB::table('overtimes')
  ->where('pegawai_id', $user_id)
  ->where('date', $date)
  ->orderBy('id', 'DESC')
  ->first();

if($data){
  if($data->end_time){
    return response()->json(['data' => $data, 'status' => 200]);
  }else{
    return response()->json(['data' => $data, 'status' => 201]);
  }
}
return response()->json(['data' => $data, 'status' => 404]);
}

public function createOT(Request $request){
  try {

    $user_id  =  Auth::user()->id;
    $date = Carbon::today()->format('Y-m-d');
    
    $data = DB::table('presences')
    ->where('pegawai_id', $user_id)
    ->where('tanggal', $date)
    ->orderBy('id', 'DESC')
    ->first();

    $start_time = Carbon::now()->format('H:i:s');
    $date = Carbon::today()->format('Y-m-d');
    $day = Carbon::now()->translatedFormat('l');

   overtime::create([
      'pegawai_id' => $user_id,
      'presensi_id' => $data->id,
      'commander' => $request->commander,
      'location' => $request->location,
      'description' => $request->description,
      'start_time' => $start_time,
      'date' => $date,
      'day' => $day,
      'status' => 'Mengajukan'
    ]);

    return response()->json(['message' =>  'Data Successfully Saved', 'status' => 200]);

    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage(),500]);
    }
}

public function endOverTime($id)
{
 try {
  $user_id  =  Auth::user()->id;
  $date = Carbon::today()->format('Y-m-d');
  $endOverTimeUser = Carbon::now()->format('H:i:s');
  $totalDurationOverTime = 0 ;
  $overTimeCost = 0 ;

  $data = DB::table('overtimes')
  ->where('pegawai_id', $user_id)
  ->where('date', $date)
  ->orderBy('id', 'DESC')
  ->first();

  $startOverTimeUser = $data->start_time;

  $start_overtime = Carbon::parse($startOverTimeUser);
  $start_endovertime = Carbon::parse($endOverTimeUser);

  $totalDurationOverTime = $start_overtime->diffInMinutes($start_endovertime);

if($totalDurationOverTime >= 120 && $totalDurationOverTime <= 179){
  $overTimeCost = 25000 ;
}elseif ($totalDurationOverTime >= 180 && $totalDurationOverTime <= 239) {
  $overTimeCost = 50000 ;
}elseif ($totalDurationOverTime >= 240 ) {
  $overTimeCost = 75000 ;
}


overtime::where('id', $id)->update(['end_time' => $endOverTimeUser, 'overtime' => $totalDurationOverTime, 'overtime_cost' => $overTimeCost]);


return response()->json(['message' => 'Data Over Time Successfully Updated',200]);
} catch (\Exception $e) {
  return response()->json(['message' => $e->getMessage(),500]);
}
}


public function updateStatusAbsensi($id, $status) {

  if($status == 'validasi'){
    DB::table('presences')->where('id', $id)->update(['status_validasi' => 'Divalidasi']);
  }else{
    DB::table('presences')->where('id', $id)->update(['status_validasi' => NULL]);
  }
 

  return response()->json(['message' => 'Data Status Validasi Successfully Updated',200]);
}

public function updateStatusAbsensiAll(Request $request) {

  $rowIds = $request->rowIds;

  DB::table('presences')->whereIn('id',  $rowIds)->update(['status_validasi' => 'Divalidasi']);

  return response()->json(['message' => 'Data Status Validasi Successfully Updated',200]);
}

// ==================//
// Role Manager
// ==================//

public function presenceManager()
{
  $user_id  =  Auth::user()->id;
        
  $employee =  User::employee($user_id);

  $departement = $employee->departemen;

  return view('manager.presence', compact('departement'));
}

public function datatableAbsensiManager($startDate = null, $endDate = null)
{
  $user_id  =  Auth::user()->id;

  $employee =  User::employee($user_id);

 $data =  DB::table('presences')->select('presences.*', 'employees.nama_pegawai');
 $data->leftJoin('employees','employees.id', 'presences.pegawai_id');

 if($employee->jabatan == 'Manager'){
  
  if ($startDate == 'null' && $endDate == 'null') {
    $from = Carbon::today();
    $to = Carbon::today();
} else {
    $from = Carbon::parse($startDate);
    $to = Carbon::parse($endDate);
}
  $data->where('departemen', $employee->departemen);
  $data->where('tanggal', '>=' ,$from);
  $data->where('tanggal', '<=' ,$to);
 }

 $data->get();

 return DataTables::of($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
}


// ================== //
// ROLE HRD
// =================//

// ==================//
// Role Manager
// ==================//

public function presenceHRD()
{
  $user_id  =  Auth::user()->id;
        
  $employee =  User::employee($user_id);

  $departement = $employee->departemen;

  return view('HRD.presence', compact('departement'));
}

public function datatableAbsensiHRD($startDate = null, $endDate = null)
{
  if ($startDate == 'null' && $endDate == 'null') {
    $from = Carbon::today();
    $to = Carbon::today();
} else {
    $from = Carbon::parse($startDate);
    $to = Carbon::parse($endDate);
}

 $data =  DB::table('presences')->select('presences.*', 'employees.nama_pegawai');
 $data->leftJoin('employees','employees.id', 'presences.pegawai_id');  
  $data->where('tanggal', '>=' ,$from);
  $data->where('tanggal', '<=' ,$to);
 $data->get();

 return DataTables::of($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
}
}
