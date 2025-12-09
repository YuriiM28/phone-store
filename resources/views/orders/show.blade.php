<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ {{ $order->order_number }} - Phone Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('phones.index') }}">
                <i class="fas fa-mobile-alt"></i> Phone Store
            </a>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Заказ {{ $order->order_number }}</h4>
                            <span class="badge 
                                @if($order->status == 'pending') bg-warning
                                @elseif($order->status == 'confirmed') bg-info
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @else bg-secondary @endif">
                                @if($order->status == 'pending') Ожидает подтверждения
                                @elseif($order->status == 'confirmed') Подтвержден
                                @elseif($order->status == 'completed') Завершен
                                @elseif($order->status == 'cancelled') Отменен
                                @else {{ $order->status }} @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Информация о заказе</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Номер заказа:</strong></td>
                                            <td>{{ $order->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Дата заказа:</strong></td>
                                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Статус:</strong></td>
                                            <td>
                                                <span class="badge 
                                                    @if($order->status == 'pending') bg-warning
                                                    @elseif($order->status == 'confirmed') bg-info
                                                    @elseif($order->status == 'completed') bg-success
                                                    @elseif($order->status == 'cancelled') bg-danger
                                                    @else bg-secondary @endif">
                                                    @if($order->status == 'pending') Ожидает подтверждения
                                                    @elseif($order->status == 'confirmed') Подтвержден
                                                    @elseif($order->status == 'completed') Завершен
                                                    @elseif($order->status == 'cancelled') Отменен
                                                    @else {{ $order->status }} @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Сумма:</strong></td>
                                            <td class="h5 text-primary">{{ number_format($order->final_amount, 0, '', ' ') }} ₽</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Контактные данные</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Имя:</strong></td>
                                            <td>{{ $order->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $order->customer_email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Телефон:</strong></td>
                                            <td>{{ $order->customer_phone }}</td>
                                        </tr>
                                        @if($order->customer_address)
                                        <tr>
                                            <td><strong>Адрес:</strong></td>
                                            <td>{{ $order->customer_address }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <h5>Товар</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            @if($order->phone->image)
                                                <img src="{{ $order->phone->image }}" class="img-fluid rounded" alt="{{ $order->phone->name }}">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                    <i class="fas fa-mobile-alt text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>{{ $order->phone->name }}</h6>
                                            <p class="text-muted mb-1">{{ $order->phone->brand->name }}</p>
                                            <small class="text-muted">{{ $order->phone->ram }}, {{ $order->phone->storage }}</small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="h5 text-primary">{{ number_format($order->phone->price, 0, '', ' ') }} ₽</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($order->notes)
                            <div class="mt-3">
                                <h6>Комментарий к заказу:</h6>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $order->notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('phones.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag"></i> Продолжить покупки
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>