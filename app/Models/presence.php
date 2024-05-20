<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presence extends Model
{
    use HasFactory;

    
         /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presences';


    
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pegawai_id', 
        'jam_masuk', 
        'jam_keluar', 
        'keterlambatan', 
        'jam_kerja', 
        'hari', 
        'tanggal', 
        'jenis_presensi', 
        'status', 
        'status_validasi'
    ];
}
