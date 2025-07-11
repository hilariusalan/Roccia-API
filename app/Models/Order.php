<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = "orders";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    public function orderItems(): HasMany {
        return $this->hasMany(Order::class, "order_id", "id");
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class, "order_id", "id");
    }

    public function shippingAdresses(): BelongsTo {
        return $this->belongsTo(Address::class, "shipping_address_id", "id");
    }

    public function billingAddresses(): BelongsTo {
        return $this->belongsTo(BillingAddress::class, "billing_address_id", "id");
    }

    public function shippingMethods(): BelongsTo {
        return $this->belongsTo(ShippingMethod::class, "shipping_method_id", "id");
    }

    public function statuses(): BelongsTo {
        return $this->belongsTo(Status::class, "status_id", "id");
    }

}
