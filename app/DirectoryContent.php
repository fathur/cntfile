<?php

namespace App;

use Exception;

/**
 * Class for handle the task.
 * This script intended for PHP 7+
 *
 * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
 */
final class DirectoryContent extends Property
{

    /**
     * @var bool
     */
    private $debug = false;

    private $elapsed = [];

    /**
     * @var string
     */
    protected $directoryPath;

    /**
     * DirectoryContent constructor.
     *
     * @param string $path
     * @throws Exception
     */
    public function __construct(string $path)
    {
        if ($this->debug)
            $this->elapsed['start'] = microtime(true);

        $this->checkVersion();

        $this->directoryPath = $path;
    }

    /**
     * Read directory recursively
     *
     * @param string $path
     */
    protected function readDirectory(string $path)
    {
        if ($handle = opendir($path)) {

            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {

                if (is_dir("{$path}/{$entry}")) {

                    if (!in_array($entry, $this->ignoredDirectories)) {

                        $this->readDirectory("{$path}/{$entry}");

                        if ($this->debug)
                            echo "Dir: '{$path}/{$entry}'\n";
                    }

                } elseif (is_file("{$path}/{$entry}")) {

                    $this->readFile("{$path}/{$entry}");
                }
            }

            closedir($handle);
        }

    }

    /**
     * Calculate file content here.
     *
     * @param string $filePath
     */
    protected function readFile(string $filePath)
    {
        $exploded = explode("/", $filePath);

        $fileName = end($exploded);

        if (!in_array($fileName, $this->ignoredFiles)) {

            $content = file_get_contents($filePath);

            if ($this->debug)
                echo "File: '{$filePath}' has content: $content\n";

            // save in store
            $this->store->addContent($content);
        }
    }

    /**
     *
     * Run the main function
     */
    public function run()
    {

        $this->readDirectory($this->directoryPath);

        foreach ($this->store->showMaxContentHashes() as $hash => $count) {
            echo "\033[0;32m{$count} {$this->store->$hash}\033[0m\n";
        }

        if ($this->debug)
            echo "\033[1;31mExecuted in: " . (microtime(true) - $this->elapsed['start']) . " s\e[0m \n";

    }
}


