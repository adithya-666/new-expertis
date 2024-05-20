<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function employee($user_id)
    {
        $employee = DB::table('employees')
        ->select('employees.id', 'employees.role_bt_id', 'employees.user_id', 'employees.nama_pegawai', 'employees.jabatan', 'employees.departemen', 'employees.unit_bisnis' , 'employees.golongan', 'employees.cabang' , 'role_bt.id as role_id', 'role_bt.jabatan as jabatan_role_bt', 'role_bt.type_1' , 'role_bt.type_2', 'role_bt.type_3' , 'role_bt.type_4' , 'role_bt.type_5', 'role_bt.type_1_next' , 'role_bt.type_2_next' , 'role_bt.type_2_next' , 'role_bt.type_3_next' , 'role_bt.type_4_next' , 'role_bt.type_5_next' , 'role_bt.kemahalan_1' , 'role_bt.kemahalan_2' , 'role_bt.kemahalan_3' , 'role_bt.akomodasi' )
        ->leftJoin('role_bt', 'role_bt.id', 'employees.role_bt_id')
        ->where('user_id', $user_id)->first();

        return $employee;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
