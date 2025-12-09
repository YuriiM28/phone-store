@extends('manager.layout')

@section('title', 'Создание промокода')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus"></i> Создание промокода</h1>
    <a href="{{ route('manager.promocodes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Назад к списку
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('manager.promocodes.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Код промокода *</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="Например: SUMMER2024" required>
                            <button type="button" class="btn btn-outline-secondary" id="generate-code">
                                <i class="fas fa-sync-alt"></i> Сгенерировать
                            </button>
                        </div>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Уникальный код, который будут вводить пользователи</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Тип скидки *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Выберите тип</option>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Процентная скидка</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Фиксированная скидка</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="value" class="form-label">Значение скидки *</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                   id="value" name="value" value="{{ old('value') }}" 
                                   min="0" step="0.01" required>
                            <span class="input-group-text" id="value-suffix">%</span>
                        </div>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="value-help">
                            Для процентной скидки: от 1 до 100%
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="min_order_amount" class="form-label">Минимальная сумма заказа</label>
                        <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" 
                               id="min_order_amount" name="min_order_amount" 
                               value="{{ old('min_order_amount') }}" min="0" step="0.01">
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Оставьте пустым, если нет ограничений</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="usage_limit" class="form-label">Лимит использований</label>
                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                               id="usage_limit" name="usage_limit" 
                               value="{{ old('usage_limit') }}" min="1">
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Максимальное количество использований</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3 form-check form-switch mt-4">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Активный промокод</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="valid_from" class="form-label">Действует с</label>
                        <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror" 
                               id="valid_from" name="valid_from" value="{{ old('valid_from') }}">
                        @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="valid_until" class="form-label">Действует до</label>
                        <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" 
                               id="valid_until" name="valid_until" value="{{ old('valid_until') }}">
                        @error('valid_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('manager.promocodes.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Создать промокод
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueSuffix = document.getElementById('value-suffix');
    const valueHelp = document.getElementById('value-help');
    const generateBtn = document.getElementById('generate-code');
    const codeInput = document.getElementById('code');

    // Обновление отображения в зависимости от типа скидки
    function updateValueDisplay() {
        if (typeSelect.value === 'percentage') {
            valueSuffix.textContent = '%';
            valueHelp.textContent = 'Для процентной скидки: от 1 до 100%';
        } else {
            valueSuffix.textContent = '₽';
            valueHelp.textContent = 'Для фиксированной скидки: сумма в рублях';
        }
    }

    typeSelect.addEventListener('change', updateValueDisplay);
    updateValueDisplay(); // Инициализация

    // Генерация кода
    generateBtn.addEventListener('click', function() {
        fetch('{{ route("manager.promocodes.generate.code") }}')
            .then(response => response.json())
            .then(data => {
                codeInput.value = data.code;
            })
            .catch(error => {
                console.error('Error generating code:', error);
            });
    });
});
</script>
@endpush
@endsection