<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'notes',
        'total_amount',
        'status',
        'phone_id',
        'user_id',
        'promocode_id',
        'discount_amount',
        'final_amount'
    ];

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = static::generateOrderNumber();

            $order->final_amount = $order->total_amount - ($order->discount_amount ?? 0);

            if (auth()->check()) {
                $order->user_id = auth()->id();
            }
        });
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', "{$prefix}{$date}%")->latest()->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $number = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $number = '0001';
        }

        return "{$prefix}{$date}{$number}";
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getStatusBadgeClassAttribute()
    {
        return [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ][$this->status] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        return [
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтвержден',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
        ][$this->status] ?? $this->status;
    }
}
