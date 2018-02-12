<?php


namespace App;

use App\Stores\FileStore;
use App\Stores\MemoryStore;
use App\Stores\SqliteStore;

trait Property
{


    /**
     * @var array
     */
    protected $ignoredDirectories = ['.', '..'];

    /**
     * @var array
     */
    protected $ignoredFiles = ['.DS_Store'];

    /**
     * @var MemoryStore|FileStore|SqliteStore
     */
    protected $store;

    /**
     * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
     */
    private function loadDriver()
    {
        switch ($this->driver) {
            case 'memory':
                $this->store = new MemoryStore();
                break;
            case 'file';
                $this->store = new FileStore();
                break;
            case 'sqlite':
                $this->store = new SqliteStore();
                break;
            default:
                $this->store = new MemoryStore();
        }
    }

    public function inMemory()
    {
        $this->store = new MemoryStore();
        return $this;
    }

    public function inFile()
    {
        $this->store = new FileStore();
        return $this;
    }

    public function inSqlite()
    {
        $this->store = new SqliteStore();
        return $this;
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
            throw new \InvalidArgumentException();
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
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function checkVersion()
    {
        $currentVersion = phpversion();

        $exploded = explode(".", $currentVersion);

        $firstElement = (int)reset($exploded);

        if ($firstElement < 7)
            throw new \Exception("You must use PHP 7+");
    }

}