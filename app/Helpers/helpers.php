<?php

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;


if (!function_exists('customPaginate')) {
    /**
     * Custom pagination for index endpoints with a default limit of 10.
     *
     * @param Model|Builder $model
     * @param array $collectionInfo An array containing properties to modify the model and collection (Currently accepted properties: sort_by_property, relations, resource, property_name).
     * @param int $limit
     * @param array $filters
     * @return array An array containing the paginated data and pagination details.
     */
    function customPaginate(Model|Builder $model, array $collectionInfo, int $limit, array $filters = []): array
    {
        $sortByProperty = $collectionInfo['sort_by_property'] ?? 'created_at';
        $sortedItems = $model->orderBy($sortByProperty, $collectionInfo['order_direction'] ?? 'asc');

        if (array_key_exists('relations', $collectionInfo)) {
            $sortedItems->with($collectionInfo['relations']);
        }

        if ($filters) {
            $sortedItems->filters($filters);
        }

        $paginatedItems = $sortedItems->paginate($limit);
        $items = $paginatedItems->items();

        $paginationData = array_diff_key($paginatedItems->toArray(), ['data' => null]);

        $resource = $collectionInfo['resource'];
        $propertyName = $collectionInfo['property_name'];

        return [
            $propertyName => $resource::collection($items),
            'pagination' => $paginationData,
        ];
    }
}

if (!function_exists('paginateCollection')) {
    /**
     * Paginate a collection of items.
     *
     * @param \Illuminate\Support\Collection $results
     * @param mixed $page
     * @param mixed $perPage
     * @param mixed $path
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function paginateCollection(Collection $collection, mixed $page = null, mixed $limit = null, mixed $path = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $perPage = $limit ?? 10;

        $items = array_values($collection->flatten()->forPage($page, $perPage)->toArray());

        $paginator = new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page
        );

        if ($path != null) {
            $paginator->setPath($path);
        }

        return $paginator;
    }
}

if (!function_exists('paginateArray')) {
    /**
     * Paginate a collection of items.
     *
     * @param \Illuminate\Support\Collection $results
     * @param mixed $page
     * @param mixed $perPage
     * @param mixed $path
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function paginateArray(array $array, mixed $page = null, mixed $limit = null, mixed $path = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $perPage = $limit ?? 10;

        $collection = collect($array);

        $items = array_values($collection->forPage($page, $perPage)->toArray());

        $paginator = new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page
        );

        if ($path != null) {
            $paginator->setPath($path);
        }

        return $paginator;
    }
}


/** WHATSAPP */
require __DIR__ . '/whatsapp.php';

/** EXTENSION */
require __DIR__ . '/extension.php';
