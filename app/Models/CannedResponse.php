<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CannedResponse extends Model
{
    protected $fillable = ['title', 'message', 'department_id'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(TicketDepartment::class);
    }
}