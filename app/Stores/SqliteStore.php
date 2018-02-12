<?php


namespace App\Stores;


class SqliteStore implements StoreInterface
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