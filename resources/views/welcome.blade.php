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
                                           placeholder="mynickname.bsky.social" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    Temporary App Password
                                </label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                    placeholder="App Password (не вводьте свій звичайний пароль сюди)"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                            <div class="row mb-0 m-5">
                                <div>
                                    <p class="lead">Для логіну ви використовуєте свій логін блюскай. В більшості випадків він виглядає як 
                                        "mynickname.bsky.social". Якщо у вас кастомний домен, наприклад @setnemo.online, - вводьте його.
                                        Ви можете ввести свій пароль, але ми наполегливо рекомендуємо використовувати тимчасові паролі, 
                                        які можна згенерувати в налаштуваннях Settings -> App Password.
                                        Їх можна і варто створювати аби логінитися своїм Bluesky аккаунтом на інших сайтах/застосунках. 
                                        Це більш безпечно, адже так ви не надаєте свій основний пароль нікому крім офіційного застосунку Bluesky.</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
