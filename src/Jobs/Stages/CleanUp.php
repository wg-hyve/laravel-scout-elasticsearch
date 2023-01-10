<?php

namespace Matchish\ScoutElasticSearch\Jobs\Stages;

use Matchish\ScoutElasticSearch\Creator\ProxyClient;
use Matchish\ScoutElasticSearch\Engines\Helpers\Index as IndexHelper;
use Matchish\ScoutElasticSearch\ElasticSearch\Params\Indices\Delete as DeleteIndexParams;
use Matchish\ScoutElasticSearch\Searchable\ImportSource;

/**
 * @internal
 */
final class CleanUp
{
    /**
     * @var ImportSource
     */
    private $source;
    private string $name;

    /**
     * @param  ImportSource  $source
     */
    public function __construct(ImportSource $source, string $name)
    {
        $this->source = $source;
        $this->name = $name;
    }

    public function handle(ProxyClient $elasticsearch): void
    {
        $indices = IndexHelper::getList($this->source->searchableAs());

        if(count($indices) > 1) {

            foreach ($indices as $index) {
                if($index !== $this->name) {
                    $params = new DeleteIndexParams((string) $index);
                    $elasticsearch->indices()->delete($params->toArray());
                }
            }
        }
    }

    public function title(): string
    {
        return 'Clean up';
    }

    public function estimate(): int
    {
        return 1;
    }
}
