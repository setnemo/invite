@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Авторизація</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="identifier" class="col-md-4 col-form-label text-md-end">
                                    bluesky nickname
                                </label>
                                <div class="col-md-6">
                                    <input id="identifier" type="text" class="form-control" name="identifier"
                                           value="zsu.bsky.social" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    Temporary App Password
                                </label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="align-items:center; justify-content: center; display:flex;">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-0 m-5">
                                <div>
                                    <p class="lead">Для логіну ви використовуєте свій логін блюскай. Я там закинув
                                        zsu.bsky.social, тому більшість людей замінить тільки zsu. Пароль можно свій,
                                        но я б рекомендував зайти в блюскай, там в налаштуваннях є App Password. Їх
                                        можно створювати щоб логінитися своїм блю скай аккаунтом на інших сайтах. Це
                                        більш безпечно для вашого аккаунту.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
