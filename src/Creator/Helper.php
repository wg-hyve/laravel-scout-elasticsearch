<?php

namespace Matchish\ScoutElasticSearch\Creator;

use Elastic\Elasticsearch\Response\Elasticsearch as Response;

class Helper
{
    /**
     * Compatibility layer between OpenSearch driver and ElasticSearch driver
     * @param Response|array $response
     * @return array
     */
    public static function convertToArray(Response|array $response): array
    {
        return is_array($response) ? $response : $response->asArray();
    }
}
