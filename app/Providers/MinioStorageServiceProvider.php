<?php
namespace App\Providers;


use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

class MinioStorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Storage::extend('minio', function ($app, $config) {
            $client = new S3Client([
                'credentials' => [
                    'key'    => $config["key"],
                    'secret' => $config["secret"]
                ],
                'region'      => $config["region"],
                'version'     => "latest",
                'endpoint'    => $config["endpoint"],
            ]);
            return new Filesystem(new AwsS3Adapter($client, $config["bucket"]));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}