<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $guarded = [];

    //Since we don't have updated_at column we can override updated_at column fiels
    const UPDATED_AT = null;
}