<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    protected $fillable = [
        'customer_id', 'name', 'registrar', 'registration_date',
        'expiry_date', 'auto_renew', 'dns_servers', 'status',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
            'expiry_date' => 'date',
            'auto_renew' => 'boolean',
            'dns_servers' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}