<?php

/**
 * @method getTransport()
 * @method getLogger()
 * @method setAsync(bool $async)
 * @method getAsync()
 * @method setElasticMetaHeader(bool $active)
 * @method getElasticMetaHeader()
 * @method setResponseException(bool $active)
 * @method getResponseException()
 * @method sendRequest($request)
 */

namespace Matchish\ScoutElasticSearch\Creator;

use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Matchish\ScoutElasticSearch\Creator\Traits\HasMagicCall;
use OpenSearch\Client as OpenSearchClient;

final class ProxyClient implements ProxyInterface
{
    use HasMagicCall;

    public function __construct(private ElasticsearchClient|OpenSearchClient $client)
    {
    }

    public function getInheritanceKey(): string
    {
        return 'client';
    }
}
