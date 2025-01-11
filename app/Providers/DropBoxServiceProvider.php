<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropBoxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config) {
            // $client = new \Google_Client();
            // $client->setClientId($config['clientId']);
            // $client->setClientSecret($config['clientSecret']);
            // $client->refreshToken($config['refreshToken']);
            // $service = new \Google_Service_Drive($client);

            // $options = [];
            // if(isset($config['teamDriveId'])) {
            //     $options['teamDriveId'] = $config['teamDriveId'];
            // }

            // $adapter = new GoogleDriveAdapter($service, $config['folderId'], $options);
            // return new \League\Flysystem\Filesystem($adapter);

            $client = new Client("bj6jef2lF_kAAAAAAAAAAUDt8kps66sJgeG3RNeRESBm225TMYvQoRIJnveNuXZx");

            return new Filesystem(new DropboxAdapter($client));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
