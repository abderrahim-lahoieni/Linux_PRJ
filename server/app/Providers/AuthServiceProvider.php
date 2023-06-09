<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use app\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
 
        Gate::define('role_president', function(User $user){

            return $user->type === 'PRESIDENT';
        });
        
        Gate::define('role_admin_univ', function(User $user){

            return $user->type === 'ADMINISTRATEUR_UNIV';
        });
       
        Gate::define('role_admin_eta', function(User $user){

            return $user->type === 'ADMINISTRATEUR_ETA';
        });
        Gate::define('role_directeur', function(User $user){

            return $user->type === 'DIRECTEUR';
      
      });
      Gate::define('role_enseignant', function(User $user){

        return $user->type === 'ENSEIGNANT';
   });

    }
    }



   




