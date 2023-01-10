<?php

namespace Matchish\ScoutElasticSearch\Jobs\Stages;

use Matchish\ScoutElasticSearch\Creator\ProxyClient;
use Matchish\ScoutElasticSearch\ElasticSearch\DefaultAlias;
use Matchish\ScoutElasticSearch\ElasticSearch\Index;
use Matchish\ScoutElasticSearch\ElasticSearch\Params\Indices\Create;
use Matchish\ScoutElasticSearch\ElasticSearch\WriteAlias;
use Matchish\ScoutElasticSearch\Searchable\ImportSource;
use Illuminate\Support\Arr;

/**
 * @internal
 */
final class CreateWriteIndex
{
    private Index $index;

    private string $name;

    /**
     * @param  ImportSource  $source
     * @param  Index  $index
     */
    public function __construct(Index $index, string $name)
    {
        $this->index = $index;
        $this->name = $name;
    }

    public function handle(ProxyClient $elasticsearch): void
    {
        $config = $this->index->config();

        Arr::forget($config, 'aliases');

        $params = new Create(
            $this->name,
            $config
        );

        $elasticsearch->indices()->create($params->toArray());
    }

    public function title(): string
    {
        return 'Create write index';
    }

    public function estimate(): int
    {
        return 1;
    }
}
