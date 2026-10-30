<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'email', 'company', 'service', 'details', 'status',
        'utm_source', 'utm_medium', 'utm_campaign', 'referrer', 'landing_page',
        'ip_address', 'user_agent', 'notified_at',
    ];

    protected function casts(): array
    {
        return ['notified_at' => 'datetime'];
    }
}
