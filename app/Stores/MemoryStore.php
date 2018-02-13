<?php


namespace App\Stores;


class MemoryStore implements StoreInterface
{
    const CHUNK_VALUE = 100;

    /**
     * Save the chunked content here.
     *
     * @var array
     */
    protected $contentHashes = [];

    /**
     * Save counted content value from content hash
     *
     * @var array
     */
    protected $countedContentHashes = [];

    /**
     * @param $content
     * @author Fathur Rohman <hi.fathur.rohman@gmail.com>
     */
    public function addContent($content)
    {
        // remove space in beginning and end
        $content = trim($content);

        // make hash for variable naming
        $contentHash = sha1($content);

        // store availability variable content hashes
        array_push($this->contentHashes, $contentHash);

        // store file content in dynamic property
        $this->{$contentHash} = $content;

        //
        if (count($this->contentHashes) == self::CHUNK_VALUE) {

            $this->calculateCountedContent();


            // reset content hashes
            $this->contentHashes = [];
        }
    }

    public function showContentHashes()
    {
        return $this->contentHashes;
    }

    /**
     *
     */
    protected function calculateCountedContent()
    {
        $newCounted = array_count_values($this->contentHashes);

        foreach ($newCounted as $hash => $count) {
            if (array_key_exists($hash, $this->countedContentHashes)) {
                $oldCount = $this->countedContentHashes[$hash];
                $newCount = $oldCount + $count;
                $this->countedContentHashes[$hash] = $newCount;
            } else {
                $this->countedContentHashes[$hash] = $count;
            }
        }

        arsort($this->countedContentHashes);

    }

    /**
     * Show max content
     *
     * @return array
     */
    public function showMaxContentHashes()
    {
        if (count($this->contentHashes) > 0) {
            $this->calculateCountedContent();

            // reset content hashes
            $this->contentHashes = [];
        }

        $max = 0;
        $data = [];
        foreach ($this->countedContentHashes as $hash => $count) {

            if ($count < $max) break;

            $max = $count;
            $data[$hash] = $count;
        }

        return $data;
    }
}