<?php


namespace App\Stores;


class FileStore implements StoreInterface
{
    /**
     * @param $content
     * @throws \ErrorException
     */
    public function addContent($content)
    {
        throw new \ErrorException('Not implemented yet');
        // TODO: Implement addContent() method.
    }

    public function showContentHashes()
    {
        // TODO: Implement showContentHashes() method.
    }
}