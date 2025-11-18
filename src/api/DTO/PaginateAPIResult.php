<?php

namespace App\DTO;

use JsonSerializable;

class PaginateAPIResult implements JsonSerializable
{
    public array $data;
    public int $page;
    public int $limit;
    public int $total;

    public function __construct(
        array $data, 
        int $page, 
        int $limit, 
        int $total
        )
    {
        $this->data = $data;
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'data' => $this->data,
            'pagination' => [
                'page' => $this->page,
                'limit' => $this->limit,
                'total' => $this->total,
                'pages' => (int) ceil($this->total / $this->limit),
            ],
        ]; 
    }
}
