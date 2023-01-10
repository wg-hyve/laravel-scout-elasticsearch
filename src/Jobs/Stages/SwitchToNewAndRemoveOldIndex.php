<?php

namespace Matchish\ScoutElasticSearch\Jobs\Stages;

use Matchish\ScoutElasticSearch\Creator\ProxyClient;
use Matchish\ScoutElasticSearch\ElasticSearch\Index;
use Matchish\ScoutElasticSearch\ElasticSearch\Params\Indices\Alias\Get;
use Matchish\ScoutElasticSearch\ElasticSearch\Params\Indices\Alias\Update;
use Matchish\ScoutElasticSearch\Searchable\ImportSource;
use Matchish\ScoutElasticSearch\Engines\Helpers\Index as IndexHelper;

/**
 * @internal
 */
final class SwitchToNewAndRemoveOldIndex
{
    /**
     * @var ImportSource
     */
    private $source;
    private string $name;

    /**
     * @param  ImportSource  $source
     * @param  Index  $index
     */
    public function __construct(ImportSource $source, string $name)
    {
        $this->source = $source;
        $this->name = $name;
    }

    public function handle(ProxyClient $elasticsearch): void
    {
        $indices = IndexHelper::getList($this->source->searchableAs());

        $params = new Update();
        foreach ($indices as $index) {
            if($index === $this->name) {
                $params->add((string) $index, $this->source->searchableAs());
                $elasticsearch->indices()->updateAliases($params->toArray());
            }
        }
    }

    public function estimate(): int
    {
        return 1;
    }

    public function title(): string
    {
        return 'Switching to the new index';
    }
}
