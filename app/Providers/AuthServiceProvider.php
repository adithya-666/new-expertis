<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access', function (User $user, $jabatan) {
            $employee = DB::table('employees')->where('user_id', $user->id)->first();
            return $employee->jabatan === $jabatan  ;
        });

        Gate::define('department', function (User $user, $departemen, $jabatan) {
            $employee = DB::table('employees')->where('user_id', $user->id)->first();
            return $employee->departemen === $departemen &&  $employee->jabatan === $jabatan  ;
        });
    }
}
