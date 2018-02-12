<?php

function dd($var)
{
    var_dump($var);
    die();
}

/**
 * Class for handle the task.
 * This script intended for PHP 7+
 *
 * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
 */
class DirectoryContent
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
     * @var array
     */
    protected $ignoredDirectories = ['.', '..'];

    /**
     * @var array
     */
    protected $ignoredFiles = ['.DS_Store'];

    /**
     * @var array
     */
    protected $contentHashes = [];

    /**
     * @var string
     */
    protected $driver = 'file'; // file, memory, sqlite

    /**
     * DirectoryContent constructor.
     *
     * @param $path
     * @throws Exception
     */
    public function __construct(string $path)
    {
        $this->checkVersion();

        $this->directoryPath = $path;
    }

    /**
     * @param string|array $fileName
     * @return $this
     * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
     */
    public function addIgnoredFile($fileName)
    {
        if (is_array($fileName)) {
            array_merge($this->ignoredFiles, $fileName);
        } elseif (is_string($fileName)) {
            array_push($this->ignoredFiles, $fileName);
        } else {
            throw new InvalidArgumentException();
        }

        return $this;
    }

    /**
     * @param string|array $directoryName
     * @return $this
     * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
     */
    public function addIgnoredDirectory($directoryName)
    {
        if (is_array($directoryName)) {
            array_merge($this->ignoredDirectories, $directoryName);
        } elseif (is_string($directoryName)) {
            array_push($this->ignoredDirectories, $directoryName);
        } else {
            throw new InvalidArgumentException();
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function checkVersion()
    {
        $currentVersion = phpversion();

        $exploded = explode(".", $currentVersion);

        $firstElement = (int)reset($exploded);

        if($firstElement < 7)
            throw new Exception("You must use PHP 7+");
    }

    // The problem with memory
    // 1. Too much files
    // 2. Big size files

    protected function readDirectory(string $path)
    {
        if ($handle = opendir($path)) {

            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {

                if (is_dir($path . "/" . $entry)) {

                    if (!in_array($entry, $this->ignoredDirectories)) {

                        $this->readDirectory($path . "/" . $entry);

                        if ($this->debug)
                            echo "Dir: '" . $path . "/" . $entry . "'\n";
                    }

                } elseif (is_file($path . "/" . $entry)) {

                    $this->readFile($path . "/" . $entry);
                }
            }

            closedir($handle);
        }

    }

    /**
     * Calculate file content here.
     *
     * @param $filePath
     */
    protected function readFile(string $filePath)
    {
        $exploded = explode("/", $filePath);

        $fileName = end($exploded);

        if (!in_array($fileName, $this->ignoredFiles)) {

            $content = file_get_contents($filePath);

            if ($this->debug)
                echo "File: '$filePath' has content: $content\n";

            // save in memory
            $this->storeInMemory($content);
        }
    }

    /**
     *
     * Run the main function
     */
    public function run()
    {

        $this->readDirectory($this->directoryPath);

        $counted = array_count_values($this->contentHashes);

        arsort($counted);

        foreach ($counted as $hash => $count) {
            echo $count . " " . $this->$hash . "\n";
            die();
        }
    }

    protected function storeInMemory($content)
    {
        // remove space in beginning and end
        $content = trim($content);

        // make hash for variable naming
        $contentHash = sha1($content);

        // store availability variable content hashes
        array_push($this->contentHashes, $contentHash);

        // store file content in dynamic property
        $this->{$contentHash} = $content;
    }
}

// Run the code!!
$dirContent = new DirectoryContent('./test');
$dirContent->run();
