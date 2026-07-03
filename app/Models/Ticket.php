<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'customer_id', 'department_id', 'subject',
        'status', 'priority', 'last_reply_at',
    ];

    protected function casts(): array
    {
        return [
            'last_reply_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(TicketDepartment::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}