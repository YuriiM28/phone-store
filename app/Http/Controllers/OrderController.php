<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Phone;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function create($phoneSlug)
    {
        $phone = Phone::where('slug', $phoneSlug)->firstOrFail();

        if ($phone->stock < 1) {
            return redirect()->route('phones.show', $phone->slug)
                ->with('error', 'Этот товар временно отсутствует в наличии.');
        }

        $userData = [];
        if (Auth::check()) {
            $user = Auth::user();
            $userData = [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_address' => $user->full_address,
            ];
        }

        return view('orders.create', compact('phone', 'userData'));
    }

    public function store(Request $request, $phoneSlug)
    {
        $phone = Phone::where('slug', $phoneSlug)->firstOrFail();

        if ($phone->stock < 1) {
            return redirect()->route('phones.show', $phone->slug)
                ->with('error', 'Этот товар временно отсутствует в наличии.');
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'promocode_id' => 'nullable|exists:promocodes,id',
            'discount_amount' => 'nullable|numeric|min:0',
        ], [
            'customer_name.required' => 'Пожалуйста, укажите ваше имя.',
            'customer_email.required' => 'Пожалуйста, укажите ваш email.',
            'customer_email.email' => 'Пожалуйста, укажите корректный email.',
            'customer_phone.required' => 'Пожалуйста, укажите ваш телефон.',
            'customer_address.required' => 'Пожалуйста, укажите адрес доставки.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $totalAmount = $phone->price;
        $discountAmount = $request->discount_amount ?? 0;
        $finalAmount = $totalAmount - $discountAmount;

        $orderData = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'notes' => $request->notes,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'phone_id' => $phone->id,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ];

        if ($request->promocode_id) {
            $orderData['promocode_id'] = $request->promocode_id;

            $promocode = Promocode::find($request->promocode_id);
            if ($promocode) {
                $promocode->incrementUsage();
            }
        }

        $order = Order::create($orderData);

        $phone->decrement('stock');

        return redirect()->route('orders.success', $order->order_number)
            ->with('success', 'Заказ успешно создан!' . ($discountAmount > 0 ? " Применена скидка {$discountAmount} ₽." : ''));
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('orders.success', compact('order'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (Auth::check() && $order->user_id !== Auth::id() && $order->customer_email !== Auth::user()->email) {
            abort(403, 'У вас нет доступа к этому заказу.');
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Пожалуйста, войдите в систему для просмотра заказов.');
        }

        $orders = Auth::user()->orders()->with('phone')->latest()->get();
        return view('orders.index', compact('orders'));
    }
}
