<?php

namespace App\Ship\Scout\Engines;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;
use Log;
use OpenSearch\Client\DocumentClient;
use OpenSearch\Client\SearchClient;
use OpenSearch\Util\SearchParamsBuilder;

class OpenSearchEngine extends Engine
{
    protected $search;
    protected $document;
    protected $columns;

    /**
     * @param Collection $models
     */
    public function update($models)
    {
        $this->document = $this->getDocument($models);
        $models->each(function ($model) {
            $array = $model->toSearchableArray();
            if (!empty($array)) {
                $this->document->add($array);
            }
        });
        $this->checkAndCommit();
    }


    public function delete($models)
    {
        $this->document = $this->getDocument($models);
        $models->each(function ($model) {
            $this->document->remove([
                'id' => $model->getKey()
            ]);
        });
        $this->checkAndCommit();
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        return $this->performSearch($builder, array_filter([
            'query' => $builder->query,
            'hits' => $builder->limit ?? config('app.paginate'),
            'appName' => $this->getAppName($builder->model),
            'format' => 'fulljson',
            'fetchFields' => $this->columns,
            'start' => 0,
        ]));
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        return $this->performSearch($builder, array_filter([
            'query' => $builder->query,
            'hits' => $perPage,
            'appName' => $this->getAppName($builder->model),
            'format' => 'fulljson',
            'fetchFields' => $this->columns,
            'start' => $perPage * ($page - 1),
        ]));
    }

    public function mapIds($results)
    {
        return collect($results['item'])->pluck('fields.id')->values();
    }

    public function map($results, $model)
    {
        if (count($results['items']) === 0) {
            return Collection::make();
        }
        $keys = collect($results['items'])
            ->pluck('fields.id')
            ->values()->map(function ($id){
                return intval($id);
            })->all();
        $models = $model->whereIn(
            $model->getQualifiedKeyName(), $keys
        )->get()->keyBy($model->getKeyName());
        return Collection::make($results['items'])->map(function ($item) use ($model, $models) {
            $key = data_get($item, 'fields.id');
            if (isset($models[$key])) {
                return $models[$key];
            }
        })->filter()->values();
    }

    public function getTotalCount($results)
    {
        return data_get($results, 'total', 0);
    }

    public function keys(Builder $builder)
    {
        return $this->mapIds($this->search($builder));
    }

    public function get(Builder $builder)
    {
        return Collection::make($this->map(
            $this->search($builder), $builder->model
        ));
    }


    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param array $options
     * @return mixed
     */
    protected function performSearch(Builder $builder, array $options = [])
    {
        /** @var  SearchClient */
        $this->search = app(SearchClient::class);
        // todo: not sure what this is about
        if ($builder->callback) {
            return call_user_func(
                $builder->callback,
                $this->search,
                $builder->query,
                $options
            );
        }
        /** @var SearchParamsBuilder $params */
        $params = new SearchParamsBuilder($options);
        // if no order specified on builder
        // order by latest id by default
        if (empty($builder->orders)) {
            $params->addSort('id', SearchParamsBuilder::SORT_DECREASE);
        }
        // apply order by clauses
        foreach ($builder->orders as $order) {
            $params->addSort($order['column'], $this->getDirectionFlag($order['direction']));
        }
        // apply where clauses
        foreach ($builder->wheres as $key => $value) {
            $params->setFilter("$key=$value");
        }
        $return = $this->search->execute($params->build());
        return $this->processResult($return);
    }

    /**
     * @param Collection $models
     * @return mixed
     */
    public function getDocument($models)
    {
        return app(DocumentClient::class)
            ->setAppName($this->getAppName($models->first()))
            ->setTable($this->getTableName($models->first()));
    }

    private function getAppName($model)
    {
        return Str::before($model->searchableAs(), '.');
    }

    private function getTableName($model)
    {
        return Str::after($model->searchableAs(), '.');
    }

    public function checkAndCommit()
    {
        if (empty($this->document->docs)) {
            return;
        }
        $return = $this->document->commit();
        $result = json_decode($return->result, true);
        if ($result['status'] != 'OK') {
            Log::error("OpenSearch update error", $result);
        }
        Log::info("OpenSearch update success");
    }

    private function processResult($result)
    {
        $searchResult = json_decode($result->result, true);
        return $searchResult['result'];
    }

    private function getDirectionFlag($direction)
    {
        switch ($direction) {
            case 'desc':
                return SearchParamsBuilder::SORT_DECREASE;
            case 'asc':
                return SearchParamsBuilder::SORT_INCREASE;
            default:
                throw new InvalidArgumentException('Unrecognized sort direction flag.');
        }
    }
}