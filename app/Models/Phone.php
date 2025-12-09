<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'screen_size',
        'ram',
        'storage',
        'camera',
        'battery',
        'processor',
        'brand_id',
        'category_id',
        'specifications'
    ];

    protected $casts = [
        'specifications' => 'array'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }


    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['brand'] ?? false, function ($query, $brand) {
            $query->whereHas('brand', function ($query) use ($brand) {
                $query->where('slug', $brand);
            });
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            });
        });

        $query->when($filters['min_price'] ?? false, function ($query, $minPrice) {
            $query->where('price', '>=', $minPrice);
        });

        $query->when($filters['max_price'] ?? false, function ($query, $maxPrice) {
            $query->where('price', '<=', $maxPrice);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        });
    }

    public function scopeSortedBy($query, $sort, $order = 'desc')
    {
        switch ($sort) {
            case 'price':
                return $query->orderBy('price', $order);
            case 'name':
                return $query->orderBy('name', $order);
            case 'created_at':
                return $query->orderBy('created_at', $order);
            case 'stock':
                return $query->orderBy('stock', $order);
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
}
