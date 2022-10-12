<?php

namespace Tests;

use Elastic\Elasticsearch\Client as ElasticsearchClient;
use OpenSearch\Client as OpenSearchClient;
use Matchish\ScoutElasticSearch\Creator\Backend;

/**
 * Class IntegrationTestCase.
 */
class IntegrationTestCase extends TestCase
{
    /**
     * @var ElasticsearchClient|OpenSearchClient
     */
    protected $elasticsearch;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->elasticsearch = $this->app->make(Backend::load()->clientClass());

        $this->elasticsearch->indices()->delete(['index' => '_all']);
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('elasticsearch', require(__DIR__.'/../config/elasticsearch.php'));
        $app['config']->set('elasticsearch.indices.mappings.products', [
            'properties' => [
                'type' => [
                    'type' => 'keyword',
                ],
                'price' => [
                    'type' => 'integer',
                ],
            ],
        ]);
    }
}
