<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Directory to store extensions
    |--------------------------------------------------------------------------
    |
    | Here you may specify the extensions directory
    | by default this has been set for you to "xtend" in the root of your project
    | You can store this anywhere you would like in your application.
    |
    */

    'directory' => 'xtend',

    /*
    |--------------------------------------------------------------------------
    | Override vendor files
    |--------------------------------------------------------------------------
    |
    | Here you may specify the vendor defaults which will be used when configuring extended packages
    | These setting will only apply to new packages that have not been configured yet
    | You will also be able to override these settings on a per package basis.
    |
    */

    'override' => [
        'config' => true,
        'css' => false,
        'js' => false,
        'translations' => true,
        'views' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Eloquent Models
    |--------------------------------------------------------------------------
    |
    | Here you may specify the global model mappings for your application - Run `xtend-laravel:update` command for changes to take effect
    | Generate IDE Helper is activated by default. All custom model methods are made available to the model you are extending, opt out by setting to false
    | You will also be able to extend models for each extension.
    |
    */

    'eloquent' => [
        'generate_ide_helper' => true,
        'extend_models' => [

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    |
    | Currently livewire components have to be registered if stored outside the App namespace
    | By defining the paths below this will automatically register all components in those specified paths.
    |
    */

    'livewire' => [
        'autoload_paths' => [

        ],
    ],
];
