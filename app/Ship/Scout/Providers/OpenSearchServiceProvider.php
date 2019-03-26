<?php

namespace App\Ship\Scout\Providers;

use App\Ship\Scout\Engines\OpenSearchEngine;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;
use Laravel\Scout\EngineManager;
use OpenSearch\Client\DocumentClient;
use OpenSearch\Client\OpenSearchClient;
use OpenSearch\Client\SearchClient;

class OpenSearchServiceProvider extends ServiceProvider
{
    protected $client;

    public function boot()
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine;
        });
    }

    public function register()
    {
        $client = new OpenSearchClient(
            config('opensearch.access_key'),
            config('opensearch.secret'),
            config('opensearch.host'),
            config('opensearch.options')
        );
        $this->app->singleton(SearchClient::class, function () use ($client) {
            return new SearchClient($client);
        });
        $this->app->singleton(DocumentClient::class, function () use ($client) {
            return new DocumentClient($client);
        });
    }

    public function provides()
    {
        return [SearchClient::class, DocumentClient::class];
    }

}