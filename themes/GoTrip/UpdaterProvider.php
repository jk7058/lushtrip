<?php
namespace Themes\GoTrip;
use Illuminate\Support\ServiceProvider;

class UpdaterProvider extends ServiceProvider
{

    public function boot(){
        if (file_exists(storage_path().'/installed') and !app()->runningInConsole()) {
            $this->runUpdateTo100();
        }
    }

    public function runUpdateTo100(){
        $version = '1.0.0';
    }
}
