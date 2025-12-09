<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'rating',
        'author_name',
        'author_email',
        'phone_id',
        'user_id'
    ];

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if (Auth::check()) {
                $review->user_id = Auth::id();
                $review->author_name = Auth::user()->name;
                $review->author_email = Auth::user()->email;
            }
        });
    }
}