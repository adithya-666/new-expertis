<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses_claim extends Model
{
    use HasFactory;

       
         /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expenses_claims';


    
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'presensi_id', 
        'pegawai_id', 
        'hari', 
        'tanggal', 
        'uang_makan', 
        'transportasi', 
        'parkir_tol', 
        'lain-lain', 
        'bukti_transportasi', 
        'bukti_parkir_tol',
        'bukti_lain_lain',
        'status_transportasi',
        'status_parkir_tol',
        'keterangan_transportasi',
        'keterangan_parkir_tol',
        'status_acc_manager',
        'status_acc_hrd',
        'status_acc_finance'
    ];
}
