<?php

namespace App\Observers;

use App\Services\BlockLedgerService;
use Illuminate\Database\Eloquent\Model;

class BlockchainObserver
{
    public function created(Model $model)
    {
        BlockLedgerService::recordModelEvent($model, 'created');
    }

    public function updated(Model $model)
    {
        BlockLedgerService::recordModelEvent($model, 'updated');
    }

    public function deleted(Model $model)
    {
        BlockLedgerService::recordModelEvent($model, 'deleted');
    }
}
