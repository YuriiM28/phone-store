<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ оформлен - Phone Store</title>
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

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-success">
                        <div class="card-body text-center py-5">
                            <div class="text-success mb-4">
                                <i class="fas fa-check-circle fa-5x"></i>
                            </div>

                            <h2 class="card-title text-success mb-3">Заказ успешно оформлен!</h2>
                            <p class="lead mb-4">Спасибо за ваш заказ. Мы свяжемся с вами в ближайшее время для подтверждения.</p>

                            <div class="card mb-4">
                                <div class="card-body text-start">
                                    <h5 class="card-title">Информация о заказе:</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Номер заказа:</strong><br>{{ $order->order_number }}</p>
                                            <p><strong>Товар:</strong><br>{{ $order->phone->name }}</p>
                                            <p><strong>Стоимость товара:</strong><br>{{ number_format($order->total_amount, 0, '', ' ') }} ₽</p>
                                            @if($order->discount_amount > 0)
                                            <p><strong>Скидка:</strong><br>-{{ number_format($order->discount_amount, 0, '', ' ') }} ₽</p>
                                            @endif
                                            <p><strong>Итого к оплате:</strong><br>{{ number_format($order->final_amount, 0, '', ' ') }} ₽</p>
                                            @if($order->promocode)
                                            <p><strong>Применен промокод:</strong><br>{{ $order->promocode->code }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Имя:</strong><br>{{ $order->customer_name }}</p>
                                            <p><strong>Email:</strong><br>{{ $order->customer_email }}</p>
                                            <p><strong>Телефон:</strong><br>{{ $order->customer_phone }}</p>
                                        </div>
                                    </div>
                                    @if($order->customer_address)
                                    <p><strong>Адрес доставки:</strong><br>{{ $order->customer_address }}</p>
                                    @endif
                                    @if($order->notes)
                                    <p><strong>Комментарий:</strong><br>{{ $order->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-clock"></i> Что дальше?</h6>
                                <ul class="mb-0 text-start">
                                    <li>В течение 1 часа с вами свяжется наш менеджер для подтверждения заказа</li>
                                    <li>Доставка осуществляется в течение 2-3 рабочих дней</li>
                                    <li>Оплата производится при получении товара</li>
                                    <li>Вы можете отслеживать статус заказа по номеру: <strong>{{ $order->order_number }}</strong></li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary me-md-2">
                                    <i class="fas fa-eye"></i> Посмотреть заказ
                                </a>
                                <a href="{{ route('phones.index') }}" class="btn btn-outline-secondary">
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