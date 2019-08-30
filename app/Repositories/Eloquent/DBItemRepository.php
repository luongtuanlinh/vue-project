<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\ItemRepository;

class DBItemRepository extends DBRepository implements ItemRepository
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function getItems($request)
    {
        return $this->model::with("item_type")
            ->paginate($request->per_page);
    }

    public function allItemWithType()
    {
        return $this->model
            ->join('item_type as c', 'c.id', 'items.type')
            ->select(
                'items.id', 'items.name as text',
                'c.id as type'
            )->get();
    }

}