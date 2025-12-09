@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Информация профиля -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Мой профиль</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h4 class="mt-3">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>

                <div class="mb-3">
                    <strong><i class="fas fa-phone"></i> Телефон:</strong>
                    <p class="mb-1">{{ $user->phone ?? 'Не указан' }}</p>
                </div>

                <div class="mb-3">
                    <strong><i class="fas fa-map-marker-alt"></i> Адрес:</strong>
                    <p class="mb-1">{{ $user->full_address ?? 'Не указан' }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100">
                    <i class="fas fa-edit"></i> Редактировать профиль
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4>{{ $orders->count() }}</h4>
                        <p class="mb-0">Всего заказов</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ $orders->where('status', 'completed')->count() }}</h4>
                        <p class="mb-0">Завершенных</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4>{{ $orders->where('status', 'pending')->count() }}</h4>
                        <p class="mb-0">Ожидают</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние заказы -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Мои заказы</h5>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                    Все заказы
                </a>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Номер заказа</th>
                                    <th>Товар</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Дата</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders->take(5) as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($order->phone->image)
                                                    <img src="{{ $order->phone->image }}" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                <span>{{ Str::limit($order->phone->name, 30) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ number_format($order->total_amount, 0, '', ' ') }} ₽</td>
                                        <td>
                                            <span class="badge {{ $order->status_badge_class }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->order_number) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5>Заказов пока нет</h5>
                        <p class="text-muted">Сделайте свой первый заказ!</p>
                        <a href="{{ route('phones.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart"></i> Перейти в каталог
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection