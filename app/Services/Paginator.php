<?php

namespace App\Services;

class Paginator
{
    public static function paginate($query, int $perPage = 10, int $page = 1)
    {
        // Calculate the offset based on page and perPage
        $offset = ($page - 1) * $perPage;

        // Clone the query for the total count without limits
        $totalQuery = clone $query;
        $total = $totalQuery->count();

        // Apply offset and limit for pagination
        $items = $query->skip($offset)->take($perPage)->get();

        // Calculate total pages
        $totalPages = ceil($total / $perPage);

        // Structure the pagination response
        return [
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => $totalPages,
            ],
        ];
    }
}
