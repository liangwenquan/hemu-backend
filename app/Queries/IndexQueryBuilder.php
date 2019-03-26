<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-15
 * Time: 17:03
 */
namespace App\Queries;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class IndexQueryBuilder
{
    private $model;

    /** @var  array */
    private $filterables;

    /** @var  array */
    private $inputs;

    /** @var  array */
    private $filterableCasts;

    /** @var  array */
    private $searchables = [];

    /** @var  Builder */
    private $query;

    /** @var  array */
    private $filters = [];

    /** @var  bool */
    private $unsortable = false;

    public function __construct(string $class = null)
    {
        if ($class) {
            $this->model = $class;
        }

        return $this;
    }

    public function setModel(string $class)
    {
        $this->model = $class;
        return $this;
    }

    public function setUnsortable()
    {
        $this->unsortable = true;
        return $this;
    }

    public function setFilterables(array $filterables)
    {
        $this->filterables = $filterables;
        return $this;
    }

    public function setSearchables(array $searchables)
    {
        $this->searchables = $searchables;
        return $this;
    }

    public function setFilterableCasts(array $casts)
    {
        $this->filterableCasts = $casts;
        return $this;
    }

    public function setInputs(array $inputs)
    {
        $this->inputs = $inputs;
        return $this;
    }

    public function addFilter($key, $value, Closure $closure)
    {
        $this->filters[] = [$key, $value, $closure];

        return $this;
    }

    public function build(): Builder
    {
        if (!$this->inputs) {
            $this->inputs = request()->all();
        }

        $this->query = $this->newBaseQuery();

        $this->buildSortQuery();
        $this->buildSearchQuery();
        collect($this->filterables)->each(function ($filterable) {
            if (!is_null($value = data_get($this->inputs, $filterable))) {
                // eg. category_id = 0 in SubjectController
                // assume you want to query with whereNull
//                if ($value == 0) {
//                    return $this->query->whereNull($filterable);
//                }

                $value = $this->castFilterableValue($filterable, $value);
                $this->query->where($filterable, $value);
            }
        });

        $this->applyFilters();
        return $this->query;
    }

    private function buildSortQuery()
    {
        if ($this->unsortable) {
            return;
        };

        $this->query->when(
            data_get($this->inputs, 'desc'),
            function (Builder $query, $descAttribute) {
                $query->orderBy($descAttribute, 'desc');
            }
        );

        $this->query->when(
            data_get($this->inputs, 'asc'),
            function (Builder $query, $descAttribute) {
                $query->orderBy($descAttribute, 'asc');
            }
        );
    }

    private function buildSearchQuery()
    {
        $searchQuery = data_get($this->inputs, 'search') ?? data_get($this->inputs, 'query');

        if (!$searchQuery) {
            return;
        }

        if ($field = data_get($this->inputs, 'field')) {
            $this->query->where($field, 'like', "%$searchQuery%");
            return;
        }

        $this->query->where(function (Builder $query) use ($searchQuery) {
            $results = [];

            foreach ($this->searchables as $field) {
                if (Str::contains($field, '.')) {
                    $results = $this->addNestedRelation($field, $results);
                } else {
                    $results[] = $field;
                }
            }

            foreach ($results as $key => $value) {
                if (is_numeric($key)) {
                    $query->orWhere($value, 'like', "%$searchQuery%");
                } else {
                    $query->orWhereHas($key, function ($query) use ($value, $searchQuery) {
                        $query->where(function (Builder $query) use ($value, $searchQuery) {
                            foreach ($value as $field) {
                                $query->orWhere($field, 'like', "%$searchQuery%");
                            }
                        });
                    });
                }
            }
        });
    }

    private function addNestedRelation($field, array $results)
    {
        $array = explode('.', $field);
        $last = array_pop($array);
        $relation = implode('.', $array);

        $results[$relation][] = $last;
        return $results;
    }

    private function castFilterableValue($field, $value)
    {
        if (is_null($this->filterableCasts)) {
            return $value;
        }

        if (!in_array($field, array_keys($this->filterableCasts))) {
            return $value;
        }

        $cast = data_get($this->filterableCasts, $field);

        if ($cast == 'integer') {
            return intval($value);
        }

        return $value;
    }

    private function newBaseQuery(): Builder
    {
        // phpcs:ignore
        return (new $this->model)->newQuery();
    }

    private function applyFilters()
    {
        foreach ($this->filters as $filter) {
            if (data_get($this->inputs, $filter[0]) == $filter[1]) {
                $filter[2]($this->query);
            }
        }
    }
}