<?php

namespace App\Services;

use App\Models\BlockLedger;

class BlockLedgerService
{
    public static function createBlock(array $payload, string $modelType = null, int $modelId = null)
    {
        $data = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $timestamp = now()->toDateTimeString();

        $lastBlock = BlockLedger::latest('id')->first();
        $previousHash = $lastBlock ? $lastBlock->current_hash : null;

        $hashInput = $data . $timestamp . ($previousHash ?? '');
        $currentHash = hash('sha256', $hashInput);

        return BlockLedger::create([
            'data' => $data,
            'timestamp' => $timestamp,
            'previous_hash' => $previousHash,
            'current_hash' => $currentHash,
            'model_type' => $modelType,
            'model_id' => $modelId,
        ]);
    }

    public static function recordModelEvent($model, string $event)
    {
        $payload = [
            'event' => $event,
            'model' => get_class($model),
            'data'  => $model->getAttributes(),
        ];

        return self::createBlock(
            $payload,
            get_class($model),
            $model->getKey()
        );
    }
}
