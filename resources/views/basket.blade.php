<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DB Test</title>
    <link rel="stylesheet" href="https://unpkg.com/tachyons@4.10.0/css/tachyons.min.css"/>
</head>
<body>
<div class="mw12 center pa6 sans-serif">
    <h1 class="mb4">Basket</h1>
    @if($empty)
        <div class="pa2 mb3 striped--near-white">
            <header class="b mb2">Корзина пуста</header>
        </div>
    @else
    @foreach($basket as $b_item)
        <p>
            {{ $b_item->product()->get()[0]->product." " }}
            {{ $b_item->material()->get()[0]->material." ".$b_item->material()->get()[0]->thickness." " }}
            {{ $b_item->length }}мм, {{ $b_item->amount }}шт
            <button class="killBtn" bi_id="{{ $b_item->id }}">Убрать из корзины</button>
        </p>
    @endforeach
    @endif
</div>
<script src="/js/basket.js" type="text/javascript"></script>
</body>
</html>
