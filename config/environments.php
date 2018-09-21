<?php

return [

    /*
     * Path to place where environments will be stored.
     * e.g. /environments or /storage/environments
     */
    'path' => 'environments/',

    /*
     * Array of files that are stored in environments.
     */
    'files' => [
        '.env',
        'phpunit.xml',
        'public/.htaccess',
        // ...
    ],

    /*
     * Clear directory from all of files stored in overwriting environment.
     * Potential danger of delete some files that are stored with purpose.
     */
    'clear_directory_when_overwriting' => false,

    /*
     * Keep existing file in base directory when this file is
     * not existing in environment that being set as active.
     */
    'keep_existing_file_when_missing' => false,
];
