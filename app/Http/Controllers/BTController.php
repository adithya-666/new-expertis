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
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Milon\Barcode\BarcodeGenerator;

class BTController extends Controller
{
    public function index()
    {
        return view('BT.index');
    }

    public function addAllowanceBT()
    {
        
         $user_id  =  Auth::user()->id;

        $employee =  User::employee($user_id);

        $employee_name = $employee->nama_pegawai;

        return view('BT.add-allowance-bt', compact('employee_name'));
    }


    public function selectColleague()
    {
        try{
            $data = DB::table('employees')->select('employees.*')->get();
            return response()->json($data);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage(), 400]);
        }
    }

    public function selectDeparture()
    {
        try{
            $data = DB::table('barcode')->select('barcode.*')->get();
            return response()->json($data);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage(), 400]);
        }
    }
    public function selectDestination($departure_id)
    {
        try{
            $data = DB::table('client_locations')->select('client_locations.*')->where('cabang_id', $departure_id )->get();
            return response()->json($data);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage(), 400]);
        }
    }

public function createBT(Request $request)
{
    try{

        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'departure' => 'required',
            'destination' => 'required',
            'day' => 'required',
            'date' => 'required',
            'transportation' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        }

        $departure = $request->departure;
        $day = $request->day;
        $date = $request->date;
        $destinations = $request->destination;
        $transportation = $request->transportation;
        $deskripsi = $request->deskripsi;
        $work_day = 0;
        $extra_price_transportation = 4000;
        $akomodasi = 0;
        $total = 0;
        $type = null;


    $departure_office = DB::table('barcode')->where('id', $departure)->first();
  
    
    $dateNow = Carbon::now()->today()->format('dmY');
    $no_urut_spj = DB::table('spj')->count() + 1; 

    $no_spj = 'BT/' .$dateNow . '-' . $no_urut_spj;
        
     $user_id  =  Auth::user()->id;

     $employee =  User::employee($user_id);

   $cl = DB::table('client_locations')->select('client_locations.*')
   ->whereIn('id', $destinations)
   ->orderBy('type', 'desc')
   ->first();

   $work_day = $day - 1;

    $type_next_field = $cl->type_next; 
  
    $type_field = $cl->type;

    $kemahalan_field = $cl->kemahalan;

    $jarak = $cl->jarak;


    $type =  DB::table('role_bt')->select($type_field)->where('id', $employee->role_bt_id)->first();

    $type_next =  DB::table('role_bt')->select($type_next_field )->where('id', $employee->role_bt_id)->first();

    $kemahalan =  DB::table('role_bt')->select($kemahalan_field )->where('id', $employee->role_bt_id)->first();


     $total_uang_saku = $type->$type_field  + ($work_day *  $type_next->$type_next_field) + $kemahalan->$kemahalan_field;

     $akomodasi = $work_day * $employee->akomodasi;


     if($transportation == 'mobil'){
        $transportation_price = 1000;
     }else if($transportation == 'motor'){
        $transportation_price = 400;
     } else if($transportation == 'umum'){
        $transportation_price = 200000;
        $jarak = 2;
     } else {
        $transportation_price = 0;
        $extra_price_transportation = 0;
     }

     $total_transportation_price = $jarak * 2 * $transportation_price + $extra_price_transportation;

     $total = $total_uang_saku + $akomodasi + $total_transportation_price ;

     $date = Carbon::createFromFormat('d/m/Y', $date);
     $date_departure = $date->format('d-m-Y');
     $date_arrived = $date->addDays($work_day)->format('d-m-Y');

     DB::table('spj')->insert([
        'employee_id' =>  $employee->user_id,
        'no_spj' =>  $no_spj,
        'commander' => 'Achmad Taufiq, S.Pd, M.M',
        'executor' => $employee->nama_pegawai,
        'transportation' =>  $transportation,
        'departure' =>  $departure_office->location,
        'destination' => $cl->location_name,
        'work_day' => $day,
        'date_departure' => $date_departure,
        'date_arrived' => $date_arrived,
        'transportation' => $transportation,
        'type_bt' => $cl->type,
        'description' => $deskripsi,
        'kemahalan' => $kemahalan->$kemahalan_field,
        'akomodasi' => $akomodasi,
        'uang_saku' => $total_uang_saku,
        'biaya_transportasi' => $total_transportation_price,
        'total' => $total,
        'status_manager' => 'Mengajukan',
        'created_at' => Carbon::now()
     ]);

    $spj =  DB::table('spj')->select('id')->latest()->first();

     DB::table('report_bt')->insert([
        'employee_id' =>  $employee->user_id,
        'spj_id' =>  $spj->id,
        'type_bt' => $type_field,
        'deposit' => $total,
        'total_cost' => 0,
        'balance' => 0
     ]);

     DB::commit();

     return response()->json(['message' => 'Data Successfully Created' , 'status' => 200]);
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json(['message' => $e->getMessage(), 400]);
    }
}


public function datatable(){
    try{
    
        $user_id  =  Auth::user()->id;

        $employee =  User::employee($user_id);
      
       $data =  DB::table('spj')->select('spj.*', 'employees.nama_pegawai' , 'report_bt.deposit' , 'report_bt.total_cost');
       $data->leftJoin('employees','employees.id', 'spj.employee_id');
       $data->leftJoin('report_bt','report_bt.spj_id', 'spj.id');

       if($employee->jabatan == 'Manager'){
        $data->where('departemen', $employee->departemen);
       } else {
        $data->where('spj.employee_id', $user_id );
       }
      
       $data->get();
      
       return DataTables::of($data)
                  ->addIndexColumn()
                  ->escapeColumns([])
                  ->make(true);

    } catch(\Exception $e){
        return response()->json(['message' => $e->getMessage(), 400]);
    }
}
    
}
