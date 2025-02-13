<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],
        'production' => [
            'driver' => 'local',
            'root' => '/mnt/volume_fra1_01/storage/app',
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'private',
            'throw' => false,
        ],
        'livewire-tmp' => [
            'driver' => 'local',
            'root' => '/mnt/volume_fra1_01/storage/app/livewire-tmp',
            'visibility' => 'private',
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => '/mnt/volume_fra1_01/storage/app/public',
            'serve' => true,
            'throw' => false,
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
