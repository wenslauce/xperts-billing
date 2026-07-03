<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TldPrice extends Model
{
    protected $fillable = ['tld', 'registrar', 'register_price', 'renew_price', 'transfer_price', 'currency'];
}