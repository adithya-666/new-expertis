<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtime extends Model
{
    use HasFactory;

             /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'overtimes';


    
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pegawai_id', 
        'presensi_id', 
        'date',
        'day',
        'commander',
        'location',
        'description',
        'start_time',
        'end_time',
        'overtime',
        'overtime_cost',
        'status'
    ];
}
