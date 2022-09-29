<?php

namespace App\Services\Brokers;

interface BrokerRequestInterface
{
    public function __construct();

    public function send();
}
