<?php

namespace App\Http;

interface RequestInterface
{
    public function execute(array $data = [], array $options = []): string;
}
