<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingAddress extends Model
{
    protected $table = "billing_addresses";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    public function orders(): HasMany {
        return $this->hasMany(BillingAddress::class, "billing_address_id", "id");
    }

}
