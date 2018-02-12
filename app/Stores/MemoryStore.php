<?php


namespace App\Stores;


class MemoryStore implements StoreInterface
{
    protected $contentHashes = [];

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
    }

    public function showContentHashes()
    {
        return $this->contentHashes;
    }
}