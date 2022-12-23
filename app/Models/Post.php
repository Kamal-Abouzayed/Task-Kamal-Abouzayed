<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'title',
        'body',
        'image',
        'pinned',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function() {
            Cache::forget('posts');
        });
    }

    public function routeNotificationForMail($notification)
    {
        return 'admin@xyz.com';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
