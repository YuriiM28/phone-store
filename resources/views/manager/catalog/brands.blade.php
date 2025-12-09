@extends('manager.layout')

@section('title', 'Управление брендами')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tag"></i> Управление брендами</h1>
</div>

<div class="row">
    <!-- Форма добавления бренда -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Добавить бренд</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manager.catalog.brands.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Название бренда *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Добавить бренд
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Список брендов -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Список брендов</h5>
            </div>
            <div class="card-body">
                @if($brands->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Slug</th>
                                    <th>Кол-во телефонов</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td>
                                            <strong>{{ $brand->name }}</strong>
                                        </td>
                                        <td>
                                            <code>{{ $brand->slug }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $brand->phones_count }}</span>
                                        </td>
                                        <td>{{ $brand->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-warning" 
                                                        data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}"
                                                        title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('manager.catalog.brands.destroy', $brand) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Удалить бренд «{{ $brand->name }}»?')" 
                                                            title="Удалить">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Модальное окно редактирования -->
                                            <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Редактировать бренд</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="{{ route('manager.catalog.brands.update', $brand) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="edit_name_{{ $brand->id }}" class="form-label">Название бренда *</label>
                                                                    <input type="text" class="form-control" 
                                                                           id="edit_name_{{ $brand->id }}" name="name" 
                                                                           value="{{ $brand->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5>Бренды не найдены</h5>
                        <p class="text-muted">Добавьте первый бренд</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection