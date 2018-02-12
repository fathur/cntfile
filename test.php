<?php

function dd($var) {
    var_dump($var); die();
}

/**
 * Class for handle the task.
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
    protected $ignoredFiles = [];

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
     */
    public function __construct($path)
    {
        $this->directoryPath = $path;
    }

    public function addIgnoredFile($fileName)
    {
        array_push($this->ignoredFiles, $fileName);

        return $this;
    }

    public function addIgnoredDirectory($directoryName)
    {
        array_push($this->ignoredDirectories, $directoryName);

        return $this;
    }

    // The problem with memory
    // 1. Too much files
    // 2. Big size files

    protected function readDirectory($path)
    {
        if ($handle = opendir($path)) {

            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {

                if (is_dir($path . "/" . $entry)) {

                    if (!in_array($entry, $this->ignoredDirectories)) {

                        $this->readDirectory($path . "/" . $entry);

                        if($this->debug)
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
    protected function readFile($filePath)
    {
        $exploded = explode("/", $filePath);

        $fileName = end($exploded);

        if (!in_array($fileName, $this->ignoredFiles)) {

            $content = file_get_contents($filePath);

            if ($this->debug)
                echo "File: '$filePath' has content: $content\n";

            // save in memory
            $this->storeInMemory($filePath, $content);
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
            echo $count . " ".$this->$hash . "\n"; die();
        }
//        dd($count);

//        $c = "1f8ac10f23c5b5bc1167bda84b833e5c057a77d2";
//        dd($this->{$c});
    }

    protected function storeInMemory($filePath, $content)
    {
        // remove space in beginning and end
        $content = trim($content);

        // make hash for variable naming
        $contentHash = sha1($content);

        // store availability variable content hashes
        array_push($this->contentHashes, $contentHash);

        // store file path in dynamic property
        $this->{$contentHash} = $content;
    }
}

// Run the code!!
$dirContent = new DirectoryContent('./test');
$dirContent->addIgnoredFile('.DS_Store');
$dirContent->run();
//
//$x = "shfgfaiguoisf jgosdf gg iofj giosf giosfd go;isfj giosfjg sofdj gsf godfs giosfdj goisdfjg adhiosfdg ;oadf ";
//
//echo md5($x);