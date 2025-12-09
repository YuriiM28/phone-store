@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-shopping-bag"></i> Мои заказы</h2>
    <a href="{{ route('phones.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-plus"></i> Новый заказ
    </a>
</div>

@if($orders->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Номер заказа</th>
                            <th>Товар</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Дата заказа</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($order->phone->image)
                                            <img src="{{ $order->phone->image }}" 
                                                 class="rounded me-2" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-mobile-alt text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $order->phone->name }}</div>
                                            <small class="text-muted">{{ $order->phone->brand->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-primary">
                                    {{ number_format($order->final_amount, 0, '', ' ') }} ₽
                                </td>
                                <td>
                                    <span class="badge {{ $order->status_badge_class }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->order_number) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Подробнее
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
        <h3>Заказов пока нет</h3>
        <p class="text-muted mb-4">Сделайте свой первый заказ в нашем магазине!</p>
        <a href="{{ route('phones.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-cart"></i> Перейти в каталог
        </a>
    </div>
@endif
@endsection