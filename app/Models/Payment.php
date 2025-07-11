<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = "payments";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    public function orders(): BelongsTo {
        return $this->belongsTo(Order::class, "order_id", "id");
    }

    public function statuses(): BelongsTo {
        return $this->belongsTo(Status::class, "payment_status_id", "id");
    }
}
