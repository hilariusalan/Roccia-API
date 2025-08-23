<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = "products";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'collection_id',
        'type_id'
    ];

    public function productUsageImages(): HasMany {
        return $this->hasMany(ProductUsageImage::class, "product_id", "id");
    }

    public function productVariants(): HasMany {
        return $this->hasMany(ProductVariant::class, "product_id", "id");
    }

    public function collections(): BelongsTo {
        return $this->belongsTo(Collection::class, "collection_id", "id");
    }

    public function types(): BelongsTo {
        return $this->belongsTo(Type::class, "type_id", "id");
    }
}
