<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hash;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

        error_reporting(0);

        //echo Hash::make('Teste123');

        session_start();

        if ($_SERVER['SERVER_NAME'] == "vankoke") {
            $url_base = config('app.url');
            $admin_base = config('app.admin');
        } else {
            $url_base = config('app.url_oficial');
            $admin_base = config('app.admin_oficial');
        }

        $data = $this->get_data($admin_base);

        if (!isset($_SESSION['admin_path'])) {
            $_SESSION['admin_path'] = $admin_base;
        }
        
        view()->share(array(
            'data' => $data,
            'url_base' => $url_base,
            'admin_base' => $admin_base
        ));
    }

    private function get_data($admin_base){

        $json = json_decode(file_get_contents($admin_base.'json/home'),true);

        return $json;
    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }
}
