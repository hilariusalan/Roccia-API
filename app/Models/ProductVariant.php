<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $table = "product_variants";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'product_id',
        'color_id',
        'fabric_id',
        'size_id',
        'image_url',
        'stock'
    ];

    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class, "product_variant_id", "id");
    }

    public function colors(): BelongsTo {
        return $this->belongsTo(Color::class, "color_id", "id");
    }

    public function fabrics(): BelongsTo {
        return $this->belongsTo(Fabric::class, "fabric_id", "id");
    }

    public function sizes(): BelongsTo {
        return $this->belongsTo(Size::class, "size_id", "id");
    }

    public function products(): BelongsTo {
        return $this->belongsTo(ProductVariant::class, "product_id", "id");
    }
}
