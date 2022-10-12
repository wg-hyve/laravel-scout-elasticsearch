<?php

namespace Matchish\ScoutElasticSearch\Creator;

interface ProxyInterface
{
    public function getInheritance(): mixed;

    public function getInheritanceKey(): string;
}
