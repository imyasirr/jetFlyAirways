<?php

namespace App\Models;

use App\Support\PublicImageStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopularDestinationGallery extends Model
{
    protected $table = 'popular_destination_gallery';

    protected $fillable = [
        'popular_destination_id',
        'image',
        'caption',
        'sort_order',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(PopularDestination::class, 'popular_destination_id');
    }

    public function imageUrl(): ?string
    {
        return PublicImageStorage::url($this->image);
    }

    /**
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'image_url' => $this->imageUrl(),
            'caption' => $this->caption,
            'sort_order' => $this->sort_order,
        ];
    }
}
