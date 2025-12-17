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

    public static function validateChain()
    {
        $blocks = BlockLedger::orderBy('id', 'asc')->get();
        $results = [
            'status' => 'SECURE',
            'message' => 'Integritas data terjamin.',
            'compromised_block' => null
        ];

        foreach ($blocks as $index => $block) {
            // 1. Validasi Hash Internal (Apakah data blok ini masih asli?)
            $hashInput = $block->data . $block->timestamp->format('Y-m-d H:i:s') . ($block->previous_hash ?? '');
            $calculatedHash = hash('sha256', $hashInput);

            if ($calculatedHash !== $block->current_hash) {
                return [
                    'status' => 'COMPROMISED',
                    'message' => "Manipulasi terdeteksi pada konten Blok #{$block->id}!",
                    'compromised_block' => $block->id
                ];
            }

            // 2. Validasi Hubungan Rantai (Apakah tersambung dengan blok sebelumnya?)
            if ($index > 0) {
                $previousBlock = $blocks[$index - 1];
                if ($block->previous_hash !== $previousBlock->current_hash) {
                    return [
                        'status' => 'COMPROMISED',
                        'message' => "Rantai terputus antara Blok #{$previousBlock->id} dan Blok #{$block->id}!",
                        'compromised_block' => $block->id
                    ];
                }
            }
        }

        return $results;
    }
}
