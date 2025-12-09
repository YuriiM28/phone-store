<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promocode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        $now = Carbon::now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->min_order_amount && $amount < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }

        return min($this->value, $amount);
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}