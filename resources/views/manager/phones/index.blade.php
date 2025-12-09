@extends('manager.layout')

@section('title', 'Управление каталогом')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-mobile-alt"></i> Управление каталогом</h1>
    <a href="{{ route('manager.phones.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Добавить телефон
    </a>
</div>

<!-- Фильтры и поиск -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('manager.phones.index') }}">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="brand" class="form-select">
                        <option value="">Все бренды</option>
                        @foreach(\App\Models\Brand::all() as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="stock" class="form-select">
                        <option value="">Все</option>
                        <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>В наличии</option>
                        <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Нет в наличии</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Применить</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($phones->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Бренд</th>
                            <th>Цена</th>
                            <th>Наличие</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phones as $phone)
                            <tr>
                                <td>
                                    @if($phone->image)
                                        <img src="{{ $phone->image }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-mobile-alt text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $phone->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $phone->category->name }}</small>
                                    </div>
                                </td>
                                <td>{{ $phone->brand->name }}</td>
                                <td>
                                    <strong class="text-primary">{{ number_format($phone->price, 0, '', ' ') }} ₽</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $phone->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $phone->stock }} шт.
                                    </span>
                                </td>
                                <td>
                                    @if($phone->stock > 0)
                                        <span class="badge bg-success">Активен</span>
                                    @else
                                        <span class="badge bg-secondary">Неактивен</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('phones.show', $phone->slug) }}" 
                                           class="btn btn-outline-primary" target="_blank" title="Просмотреть в магазине">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('manager.phones.edit', $phone) }}" 
                                           class="btn btn-outline-warning" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('manager.phones.destroy', $phone) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Удалить этот телефон?')" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Пагинация -->
            <div class="d-flex justify-content-center mt-4">
                {{ $phones->links() }}
            </div>
        </div>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-mobile-alt fa-4x text-muted mb-3"></i>
        <h3>Телефоны не найдены</h3>
        <p class="text-muted mb-4">Добавьте первый телефон в каталог</p>
        <a href="{{ route('manager.phones.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Добавить телефон
        </a>
    </div>
@endif
@endsection