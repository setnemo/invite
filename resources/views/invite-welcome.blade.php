@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Вітаємо!</div>
                    <div class="card-body">
                        <div class="row mb-3 m-2">
                            <p>Ваш номер в черзі: <span class="text-success">{{ $count }}</span></p>
                        </div>
                        <div class="col-md-12">
                            <div style="align-items:center; justify-content: center; display:flex;">
                                <a href="{{ route('welcome') }}" class="btn btn-primary">
                                    На головну
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
