<?php

use App\DirectoryContent;

require __DIR__.'/vendor/autoload.php';

// Run the code!!
$dirContent = new DirectoryContent('./test');
$dirContent->inMemory()->run();