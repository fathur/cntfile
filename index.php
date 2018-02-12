<?php

use App\DirectoryContent;

require __DIR__.'/vendor/autoload.php';

// Run the code!!
try {
    $dirContent = new DirectoryContent('./test');
    $dirContent->inMemory()->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
