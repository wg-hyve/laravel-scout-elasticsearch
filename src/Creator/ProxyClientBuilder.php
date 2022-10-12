<?php

/**
 * @method getTransport()
 */

namespace Matchish\ScoutElasticSearch\Creator;

use Elastic\Elasticsearch\ClientBuilder as ElasticsearchClientBuilder;
use Matchish\ScoutElasticSearch\Creator\Traits\HasMagicCall;
use OpenSearch\ClientBuilder as OpenSearchClientBuilder;

final class ProxyClientBuilder implements ProxyInterface
{
    use HasMagicCall;

    private function __construct(private ElasticsearchClientBuilder|OpenSearchClientBuilder $clientBuilder)
    {
    }

    public static function create(): self
    {
        return new self(Backend::load()->clientBuilder());
    }

    public static function fromConfig(array $config, bool $quiet = false): ProxyClient
    {
        return new ProxyClient(Backend::load()->clientBuilderClass()::fromConfig($config, $quiet));
    }

    public function build(): ProxyClient
    {
        return new ProxyClient($this->clientBuilder->build());
    }

    public function getInheritanceKey(): string
    {
        return 'clientBuilder';
    }
}
