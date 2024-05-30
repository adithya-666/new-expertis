<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\expenses_claim;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ecExport;

class ECController extends Controller
{
    public function index()
    {
        $user_id  =  Auth::user()->id;

        $employee =  User::employee($user_id);
    
        $jabatan = $employee->jabatan;

        return view('EC.index', compact('jabatan'));
    }

    public function datatable($startDate, $endDate)
    {

        $user_id  =  Auth::user()->id;

        $employee =  User::employee($user_id);
      
       $data =  DB::table('expenses_claims')->select('expenses_claims.*', 'employees.nama_pegawai');
       $data->leftJoin('employees','employees.id', 'expenses_claims.pegawai_id');
      
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

    public function addEC(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                 'file_transportasi' => 'image|file',
                 'file_parkir_tol' => 'image|file'
            ]);

            if($validator->fails()){
    
                return response()->json([
                    'status' => 400,
                    'messages' => $validator->getMessageBag()
                ]);
    
            }

            if($request->transportasi !== null ){
                if($request->file('file_transportasi')){
                    
                    $fileNameExists = DB::table('expenses_claims')->where('id', $request->ec_id )->first();
                    // Check if exists file after saved in folder ec
                        if(File::exists(public_path('storage/ec/'. $fileNameExists->bukti_transportasi))){
                            File::delete(public_path('storage/ec/'. $fileNameExists->bukti_transportasi));
                        }
                
                    $image = $request->file('file_transportasi');
                    $filenameWithExt = $request->file('file_transportasi')->getClientOriginalName();
                    $fileName = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extensions = $request->file('file_transportasi')->getClientOriginalExtension();
                    $fileNameSave = Auth::user()->username.'-'. date('d-M-Y-H-i-s') . '-transportasi' . '.'.$extensions;
        
                    // Source : https://image.intervention.io/v3
                    $image_resize = Image::make($image->getRealPath());     
                    // now you are able to resize the instance
                    $image_resize->resize(250, 250, function ($constraint) {
                        $constraint->aspectRatio();
                    });
        
                    // Adjust the quality as needed
                    $compressedImage = $image_resize->encode('png', 100);
        
                    // finally we save the image as a new file
                    $compressedImage->save(public_path('storage/ec/'. $fileNameSave));

                 $data['bukti_transportasi']  = $fileNameSave;
                }
                $transportasi = str_replace(',', '', $request->transportasi);
                $data['transportasi']  = $transportasi;
                $data['status_transportasi']  = 'Mengajukan';
            }

            if($request->parkir_tol !== null ){
                if($request->file('file_parkir_tol')){
                    $fileNameExists = DB::table('expenses_claims')->where('id', $request->ec_id )->first();
                     // Check if exists file after saved in folder ec
                     if(File::exists(public_path('storage/ec/'. $fileNameExists->bukti_parkir_tol))){
                        File::delete(public_path('storage/ec/'. $fileNameExists->bukti_parkir_tol));
                    }
                    $image = $request->file('file_parkir_tol');
                    $filenameWithExt = $request->file('file_parkir_tol')->getClientOriginalName();
                    $fileName = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extensions = $request->file('file_parkir_tol')->getClientOriginalExtension();
                    $fileNameSave = Auth::user()->username.'-'. date('d-M-Y-H-i-s') . '-parkir-tol' .'.'.$extensions;
        
                    // Source : https://image.intervention.io/v3
                    $image_resize = Image::make($image->getRealPath());     
                    // now you are able to resize the instance
                    $image_resize->resize(250, 250, function ($constraint) {
                        $constraint->aspectRatio();
                    });
        
                    // Adjust the quality as needed
                    $compressedImage = $image_resize->encode('png', 100);
        
                    // finally we save the image as a new file
                    $compressedImage->save(public_path('storage/ec/'. $fileNameSave));

                 $data['bukti_parkir_tol']  = $fileNameSave;
                 $data['status_parkir_tol']  = 'Mengajukan';
                }
                $parkir_tol = str_replace(',', '', $request->parkir_tol);
                $data['parkir_tol'] = $parkir_tol;
            }

            expenses_claim::where('id', $request->ec_id)->update($data);

            return response()->json(['message' => 'Successfully Add EC', 'status' => 200]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    
    }

    public function checkEC(Request $request)
    {
        try{

       $data = DB::table('expenses_claims')->where('id', $request->id)->first();

       return response()->json($data);

        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }


    // ============//
    // ROLE MANAGER
    // ==========//

    public function expensesClaimsManager()
    {
        $user_id  =  Auth::user()->id;
        
        $employee =  User::employee($user_id);

        $departement = $employee->departemen;

        return view('manager.expenses-claims', compact('departement'));
    }


    public function datatableExplainsClaimsManager($startDate , $endDate)
    {
        $user_id  =  Auth::user()->id;
        
        $employee =  User::employee($user_id);
      
       $data =  DB::table('expenses_claims')->select('expenses_claims.*', 'employees.nama_pegawai');
       $data->leftJoin('employees','employees.id', 'expenses_claims.pegawai_id');
      
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


    public function updateStatusEC(Request $request, $id, $status)
    {
        try{

            $status_transportasi = $request->status_transportasi == 'null' ? NULL : ($request->status_transportasi == 'Mengajukan' ? 'Diterima' : 'Mengajukan');
            $status_parkir_tol = $request->status_parkir_tol == 'null' ? NULL : ($request->status_parkir_tol == 'Mengajukan' ? 'Diterima' : 'Mengajukan');
    

            switch ($status) {
                case 'diterima':
                    expenses_claim::where('id', $id)
                    ->update(['status_acc_manager' => 'Diterima',
                                'status_transportasi' => $status_transportasi,
                                'status_parkir_tol' => $status_parkir_tol
                            ]);
                    break;
                
                default:
                expenses_claim::where('id', $id)
                ->update(['status_acc_manager' => null,
                'status_transportasi' => $status_transportasi,
                'status_parkir_tol' => $status_parkir_tol
                     ]);
                    break;
            }

            return response()->json(['message' => 'Successfully update status', 200]);

        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function updateStatusECTransportasi(Request $request, $id)
    {
        try{

            expenses_claim::where('id', $id)
            ->update([
                        'status_transportasi' => $request->status
                    ]);

            return response()->json(['message' => 'Successfully update status', 200]);

        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

   
// ==============================//
// Role HRD
//==============================//

    public function expensesClaimHRD(){

        $user_id  =  Auth::user()->id;
        
        $employee =  User::employee($user_id);

        $departement = $employee->departemen;

        return view('HRD.expenses-claims', compact('departement'));
    }

    public function datatableExplainsClaimHRD($startDate = null , $endDate = null, $employee = null, $departement = null, $unit_bisnis = null)
    {
        try{
          
           $data =  DB::table('expenses_claims')->select('expenses_claims.*', 'employees.nama_pegawai' , 'employees.departemen');
           $data->leftJoin('employees','employees.id', 'expenses_claims.pegawai_id'); 
            if ($startDate == null && $endDate == null) {
              $from = Carbon::today();
              $to = Carbon::today();
          } else {
              $from = Carbon::parse($startDate);
              $to = Carbon::parse($endDate);
          }
          if($employee !== null && $employee !== 'null'){
            $data->where('employees.id', $employee);
          }
          if($departement !== null && $departement !== 'null'){
            $data->where('employees.departemen', $departement);
          }
          if($unit_bisnis !== null && $unit_bisnis !== 'null'){
            $data->where('employees.unit_bisnis', $unit_bisnis);
          }
            $data->where('expenses_claims.status_acc_manager', 'Diterima');
            $data->where('expenses_claims.tanggal', '>=' ,$from);
            $data->where('expenses_claims.tanggal', '<=' ,$to);
           $data->get();
           
            return DataTables::of($data)
                       ->addIndexColumn()
                       ->escapeColumns([])
                       ->make(true);

        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }


    public function updateStatusEcHRD(Request $request, $id, $status)
    {
        try{

            $status_transportasi = $request->status_transportasi == 'null' ? NULL : ($request->status_transportasi == 'Diterima' ? 'Divalidasi' : 'Diterima');
            $status_parkir_tol = $request->status_parkir_tol == 'null' ? NULL : ($request->status_parkir_tol == 'Diterima' ? 'Divalidasi' : 'Diterima');
    

            switch ($status) {
                case 'validasi':
                    expenses_claim::where('id', $id)
                    ->update(['status_acc_hrd' => 'Divalidasi',
                                'status_transportasi' => $status_transportasi,
                                'status_parkir_tol' => $status_parkir_tol
                            ]);
                    break;
                
                default:
                expenses_claim::where('id', $id)
                ->update(['status_acc_hrd' => null,
                'status_transportasi' => $status_transportasi,
                'status_parkir_tol' => $status_parkir_tol
                     ]);
                    break;
            }

            return response()->json(['message' => 'Successfully update status', 200]);

        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }

    public function  selectUnitBisnis(Request $request){
        try{
      
          $search = $request['query'];
      
        $data =  DB::table('employees')->distinct()
        ->where('departemen', 'LIKE', "%{$search}%" )
        ->where('departemen', '!=', '' )
        ->get(['employees.unit_bisnis']);
      
        return response()->json($data);
      
        } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage(),500]);
        }
      }
      
      public function  selectEmployee(Request $request){
        try{
      
          $search = $request['query'];
      
        $data =  DB::table('employees')->select('employees.nama_pegawai', 'employees.id')
        ->where('nama_pegawai', 'LIKE', "%{$search}%" )
        ->where('employees.id', '!=', 1 )
        ->where('employees.id', '!=', 2 )
        ->get();
      
        return response()->json($data);
      
        } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage(),500]);
        }
      }
      
      
      public function  selectDepartment(Request $request){
        try{
      
          $search = $request['query'];
      
        $data =  DB::table('employees')->distinct()
        ->where('departemen', 'LIKE', "%{$search}%" )
        ->where('departemen', '!=' ,'General Manager' )
        ->get(['employees.departemen']);
      
        return response()->json($data);
      
        } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage(),500]);
        }
      }

      public function exportExcel($startDate = null, $endDate = null, $employee_id = null, $departement = null, $unit_bisnis = null){
        try {
        
          if ($startDate == null && $endDate == null) {
            $from = Carbon::today();
            $to = Carbon::today();
        } else {
            $from = Carbon::parse($startDate);
            $to = Carbon::parse($endDate);
        }
      
 
      
        $data =  DB::table('expenses_claims');
        $data->select('employees.nama_pegawai', DB::raw('SUM(expenses_claims.uang_makan)  as total_uang_makan'), DB::raw('SUM(expenses_claims.transportasi)  as total_transportasi'), DB::raw('SUM(expenses_claims.parkir_tol)  as total_parkir_tol'), DB::raw('SUM(expenses_claims.lain_lain)  as total_lain_lain'), DB::raw('SUM(expenses_claims.transportasi + expenses_claims.lain_lain)  as total'));
        $data->leftJoin('employees', 'employees.id', 'expenses_claims.pegawai_id');
        if($departement !== null && $departement !== 'null'){
          $data->where('employees.departemen',  $departement);
        }
        if($employee_id  !== null && $employee_id  !== 'null'){
          $data->where('employees.id',  $employee_id);
        }
        if($unit_bisnis  !== null && $unit_bisnis  !== 'null'){
          $data->where('employees.unit_bisnis',  $unit_bisnis);
        }
        $data->where('expenses_claims.tanggal', '>=', $from);
        $data->where('expenses_claims.tanggal', '<=', $to);
        $data->where('expenses_claims.status_acc_hrd', 'Divalidasi');
        $data->groupBy('expenses_claims.pegawai_id');
       $expenses_claims = $data->get();

            //  dd( $expenses_claims );
      
      
      
        if ($startDate == null && $endDate == null) {
          $from = Carbon::today()->now()->format('d-m-Y');
          $to = Carbon::today()->now()->format('d-m-Y');
      } else {
          $from = Carbon::parse($startDate)->format('d-m-Y');
          $to = Carbon::parse($endDate)->format('d-m-Y');
      }
      
        $information['startDate'] = $from;
        $information['endDate'] = $to;
        if($employee_id !== null && $employee_id !== 'null'){
          $employee_name = DB::table('employees')->where('id', $employee_id)->first();
          $information['employee_name'] =    $employee_name->nama_pegawai;
        }else{
          $information['employee_name'] = '-';
        }
        $information['departement'] = $departement !== 'null' ? $departement : '-';
        $information['unit_bisnis'] = $unit_bisnis !== 'null' ? $unit_bisnis : '-';
      
      
        $fileName = 'Expenses Claims.xlsx'; // Nama file Excel yang akan diunduh
      
        return Excel::download(new ecExport($expenses_claims, $information), $fileName);
      
        
        }  catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage(),500]);
        }
      }
}
