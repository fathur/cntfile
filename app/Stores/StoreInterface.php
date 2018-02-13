<?php

namespace App\Stores;


interface StoreInterface
{
    public function addContent($content);

    public function showContentHashes();

    public function showMaxContentHashes();
}