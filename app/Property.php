<?php


namespace App;

use App\Stores\MemoryStore;

abstract class Property
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
     * @var string
     */
    protected $driver = 'memory'; // file, memory, sqlite

    /**
     * @var MemoryStore
     */
    protected $store;

    /**
     * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
     */
    protected function loadDriver()
    {
        switch ($this->driver) {
            case 'memory':
                $this->store = new MemoryStore();
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