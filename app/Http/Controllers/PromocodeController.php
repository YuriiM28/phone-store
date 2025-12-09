<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'order_amount' => 'required|numeric|min:0'
        ]);

        $promocode = Promocode::where('code', $request->code)->first();

        if (!$promocode) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод не найден'
            ]);
        }

        if (!$promocode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Промокод недействителен или истек срок действия'
            ]);
        }

        $discount = $promocode->calculateDiscount($request->order_amount);

        if ($discount === 0 && $promocode->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Минимальная сумма заказа для этого промокода: ' . number_format($promocode->min_order_amount, 0, '', ' ') . ' ₽'
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'discount_formatted' => number_format($discount, 0, '', ' '),
            'final_amount' => $request->order_amount - $discount,
            'final_amount_formatted' => number_format($request->order_amount - $discount, 0, '', ' '),
            'promocode' => [
                'id' => $promocode->id,
                'code' => $promocode->code,
                'type' => $promocode->type,
                'value' => $promocode->value
            ]
        ]);
    }
}