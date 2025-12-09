@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Редактирование профиля</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <h6 class="mb-3">Основная информация</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">ФИО *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   placeholder="+7 (999) 999-99-99">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Город</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                   id="city" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Адрес</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" 
                                  placeholder="Улица, дом, квартира">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="postal_code" class="form-label">Почтовый индекс</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                               id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">Смена пароля</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">Текущий пароль</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">Новый пароль</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="new_password_confirmation" class="form-label">Подтверждение пароля</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Заполните поля пароля только если хотите изменить его. 
                            Эти данные будут использоваться для предзаполнения форм заказов.
                        </small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection