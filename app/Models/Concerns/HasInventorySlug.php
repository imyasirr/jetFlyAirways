<?php

namespace App\Models\Concerns;

use App\Support\InventorySlug;
use Illuminate\Database\Eloquent\Model;

trait HasInventorySlug
{
    public static function bootHasInventorySlug(): void
    {
        static::created(function (Model $model) {
            if (filled($model->slug)) {
                return;
            }
            $slug = InventorySlug::forModel($model);
            if ($slug === '') {
                return;
            }
            $model->slug = $slug;
            $model->saveQuietly();
        });
    }
}
