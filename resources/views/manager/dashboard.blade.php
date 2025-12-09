@extends('manager.layout')

@section('title', 'Дашборд')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt"></i> Дашборд</h1>
</div>

<!-- Статистика -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['phones_count'] }}</h4>
                        <p class="mb-0">Телефонов</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-mobile-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['orders_count'] }}</h4>
                        <p class="mb-0">Заказов</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-bag fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['users_count'] }}</h4>
                        <p class="mb-0">Пользователей</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['reviews_count'] }}</h4>
                        <p class="mb-0">Отзывов</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comments fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Быстрые действия</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.phones.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Добавить телефон
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.promocodes.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-tag"></i> Создать промокод
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.phones.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-list"></i> Все телефоны
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.promocodes.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-tags"></i> Все промокоды
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.catalog.brands') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-tag"></i> Управление брендами
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('manager.catalog.categories') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-folder"></i> Управление категориями
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Последние промокоды -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tags"></i> Недавние промокоды</h5>
                <a href="{{ route('manager.promocodes.index') }}" class="btn btn-sm btn-outline-success">Все промокоды</a>
            </div>
            <div class="card-body">
                @php
                    $recentPromocodes = \App\Models\Promocode::latest()->take(5)->get();
                @endphp
                
                @if($recentPromocodes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Код</th>
                                    <th>Тип</th>
                                    <th>Значение</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPromocodes as $promocode)
                                    <tr>
                                        <td>
                                            <a href="{{ route('manager.promocodes.edit', $promocode) }}" class="text-decoration-none">
                                                <code>{{ $promocode->code }}</code>
                                            </a>
                                        </td>
                                        <td>
                                            @if($promocode->type === 'percentage')
                                                <span class="badge bg-info">Процент</span>
                                            @else
                                                <span class="badge bg-primary">Фиксированный</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($promocode->type === 'percentage')
                                                {{ $promocode->value }}%
                                            @else
                                                {{ number_format($promocode->value, 0, '', ' ') }} ₽
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $promocode->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $promocode->is_active ? 'Активен' : 'Неактивен' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">Промокоды еще не созданы</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Последние телефоны -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Недавно добавленные телефоны</h5>
                <a href="{{ route('manager.phones.index') }}" class="btn btn-sm btn-outline-primary">Все телефоны</a>
            </div>
            <div class="card-body">
                @php
                    $recentPhones = \App\Models\Phone::with('brand')->latest()->take(5)->get();
                @endphp
                
                @if($recentPhones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Изображение</th>
                                    <th>Название</th>
                                    <th>Бренд</th>
                                    <th>Цена</th>
                                    <th>Наличие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPhones as $phone)
                                    <tr>
                                        <td>
                                            @if($phone->image)
                                                <img src="{{ $phone->image }}" style="width: 40px; height: 40px; object-fit: cover;" class="rounded">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-mobile-alt text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('manager.phones.edit', $phone) }}" class="text-decoration-none">
                                                {{ \Illuminate\Support\Str::limit($phone->name, 20) }}
                                            </a>
                                        </td>
                                        <td>{{ $phone->brand->name }}</td>
                                        <td>{{ number_format($phone->price, 0, '', ' ') }} ₽</td>
                                        <td>
                                            <span class="badge {{ $phone->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $phone->stock }} шт.
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">Телефоны еще не добавлены</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection