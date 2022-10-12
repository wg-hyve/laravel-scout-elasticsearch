<?php

declare(strict_types=1);

namespace Matchish\ScoutElasticSearch;

use Illuminate\Support\ServiceProvider;
use Matchish\ScoutElasticSearch\Creator\ProxyClient;
use Matchish\ScoutElasticSearch\Creator\ProxyClientBuilder;
use Matchish\ScoutElasticSearch\ElasticSearch\Config\Config;
use Matchish\ScoutElasticSearch\ElasticSearch\EloquentHitsIteratorAggregate;
use Matchish\ScoutElasticSearch\ElasticSearch\HitsIteratorAggregate;

final class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/elasticsearch.php', 'elasticsearch');

        $this->app->bind(ProxyClient::class, function () {
            $clientBuilder = ProxyClientBuilder::create()->setHosts(Config::hosts());
            if ($user = Config::user()) {
                $clientBuilder->setBasicAuthentication($user, Config::password());
            }

            if ($cloudId = Config::elasticCloudId()) {
                $clientBuilder->setElasticCloudId($cloudId)
                    ->setApiKey(Config::apiKey());
            }

            return $clientBuilder->build();
        });

        $this->app->bind(
            HitsIteratorAggregate::class,
            EloquentHitsIteratorAggregate::class
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/elasticsearch.php' => config_path('elasticsearch.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return [ProxyClient::class];
    }
}
