<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelDaily\Invoices\Classes\Buyer;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'path',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }





}
