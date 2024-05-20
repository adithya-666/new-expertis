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
                case 'validasi':
                    expenses_claim::where('id', $id)
                    ->update(['status_acc_manager' => 'Divalidasi',
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
}
