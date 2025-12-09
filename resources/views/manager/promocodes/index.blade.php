@extends('manager.layout')

@section('title', 'Управление промокодами')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tags"></i> Управление промокодами</h1>
    <a href="{{ route('manager.promocodes.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Создать промокод
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($promocodes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Код</th>
                            <th>Тип</th>
                            <th>Значение</th>
                            <th>Мин. заказ</th>
                            <th>Лимит</th>
                            <th>Использовано</th>
                            <th>Период действия</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promocodes as $promocode)
                            <tr>
                                <td>
                                    <code class="fs-6">{{ $promocode->code }}</code>
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
                                    @if($promocode->min_order_amount)
                                        {{ number_format($promocode->min_order_amount, 0, '', ' ') }} ₽
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($promocode->usage_limit)
                                        {{ $promocode->usage_limit }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $promocode->used_count }}
                                </td>
                                <td>
                                    @if($promocode->valid_from && $promocode->valid_until)
                                        {{ $promocode->valid_from->format('d.m.Y') }} - {{ $promocode->valid_until->format('d.m.Y') }}
                                    @else
                                        <span class="text-muted">Без ограничений</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $promocode->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $promocode->is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('manager.promocodes.edit', $promocode) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('manager.promocodes.destroy', $promocode) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Удалить промокод?')">
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
                {{ $promocodes->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h4>Промокоды не найдены</h4>
                <p class="text-muted">Создайте первый промокод для вашего магазина</p>
                <a href="{{ route('manager.promocodes.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Создать промокод
                </a>
            </div>
        @endif
    </div>
</div>
@endsection