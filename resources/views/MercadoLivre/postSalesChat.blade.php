@extends('layouts.app')

@section('content')
    @livewire('mercado-livre.post-sales-chat', ['orderId' => $orderId, 'sellerId' => $sellerId])
@endSection