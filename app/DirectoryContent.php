<?php

namespace App;

use Exception;

/**
 * Class for handle the task.
 * This script intended for PHP 7+
 *
 * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
 */
class DirectoryContent extends Property
{

    /**
     * @var bool
     */
    private $debug = false;

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

        $counted = array_count_values($this->store->showContentHashes());

        arsort($counted);

        foreach ($counted as $hash => $count) {
            echo "{$count} {$this->store->$hash} \n";
            die();
        }
    }
}


