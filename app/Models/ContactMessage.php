<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'locale',
        'status',
        'ip',
        'user_agent',
    ];
}
