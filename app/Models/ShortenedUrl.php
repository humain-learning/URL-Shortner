<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortenedUrl extends Model
{
    //

    protected $fillable = [
        'original_url',
        'shortened_url',
        'expires_at',
        'clicks',
        'ip',
    ];
}
