<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait helperTrait
{
    public static function getRandomUniqueIdForModel()
    {
        $uniqueIds = array_column(self::get(['unique_id'])->toArray(), 'unique_id');;
        do {
            $uniqueId = Str::random(12);
        } while (in_array($uniqueId, $uniqueIds));
        return $uniqueId;
    }
}
