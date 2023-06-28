@extends('layouts.app')

@section('content')
    <?php
    $account = json_decode(session()->get('acc', '{}'), true);
    $data    = json_decode(session()->get('codes', '{}'), true);
    $codes   = $data['codes'] ?? [];
    $trains  = \App\Models\InviteCode::getCodesByHandle($account['handle'] ?? '');
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Мої інвайт коди</div>
                    <div class="card-body">
                        @if(!empty($codes))
                            <form method="POST" action="{{ route('donate') }}">
                                @csrf
                                <div class="row mb-3 p-3">
                                    @foreach($codes as $code)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="{{ $code['code'] }}" name="{{ $code['code'] }}"
                                                    {{ !empty($code['uses']) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $code['code'] }}">
                                                    {{ $code['code'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mb-0 p-3">
                                    <select class="form-select form-select-lg mb-1" aria-label=".form-select-xs"
                                            name="train" required>
                                        @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                                            <option
                                                value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button id="donate" type="submit" class="btn btn-dark" disabled>
                                            Віддати жебракам
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                @foreach($trains as $name => $items)
                    <div class="card mt-2">
                        <div class="card-header">{{ $name }}</div>
                        <div class="card-body">
                            <div class="row mb-4 p-3">
                                @foreach($items as $item)
                                    <div class="col-md-4 border border-success m-1">
                                        <hr>
                                        Дарує <a href="https://bsky.app/profile/{{ $item->giver_handle }}"
                                                 target="_blank">{{ $item->giver_handle }}</a>
                                        <hr>
                                        Надано {{ $item->created_at }}
                                        <hr>
                                        @if($item->booked_at)
                                            <span data-id="{{ $item->id }}"
                                                  class="btn btn-xs btn-success request-button-unbook"
                                                  data-code="{{ $item->code }}"
                                                  data-handle='https://bsky.app/profile/{{ $item->giver_handle }}'
                                                  title="Разбукати Invite Code">
                                            <i class="fa fa-share" aria-hidden="true"></i> Разбукати
                                            </span>
                                            <hr>
                                            <span data-id="{{ $item->id }}"
                                                  class="btn btn-xs btn-danger request-button-forget"
                                                  data-code="{{ $item->code }}"
                                                  data-handle='https://bsky.app/profile/{{ $item->giver_handle }}'
                                                  title="Разбукати Invite Code">
                                            <i class="fa fa-times" aria-hidden="true"></i> Забути
                                            </span>
                                            <hr>
                                            <span data-id="{{ $item->id }}"
                                                  class="btn btn-xs btn-primary request-button-text"
                                                  data-code="{{ $item->code }}"
                                                  data-handle='https://bsky.app/profile/{{ $item->giver_handle }}'
                                                  title="Разбукати Invite Code">
                                            <i class="fa fa-eye" aria-hidden="true"></i> Подивитись
                                            </span>
                                        @else
                                            <span data-id="{{ $item->id }}"
                                                  class="btn btn-xs btn-warning request-button-book"
                                                  data-code="{{ $item->code }}"
                                                  data-handle='https://bsky.app/profile/{{ $item->giver_handle }}'
                                                  title="Забукати Invite Code">
                                            <i class="fa fa-book" aria-hidden="true"></i> Забукати
                                        </span>
                                        @endif
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div id="myModal" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Код забукано!</h5>
                            </div>
                            <div class="modal-body">
                                <p>Вітаємо!</p>
                                <p>Ваш інвайт код:</p>
                                <p>>>>> <span id="code"></span> <<<<</p>
                                <p>Android:
                                    https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1</p>
                                <p>iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199</p>
                                <p>Desktop: https://bsky.app</p>
                                <p>Гайди по блускай, та кого пофоловити можна знайти тут: https://faq.uabluerail.org</p>
                                <p>Якщо ваша ласка, напишіть нам коли успішно зареєструєтесь ваш нік в системі для
                                    звітності.</p>
                                <p>Ваш спонсор:</p>
                                <p><span id="giver"></span></p>
                                <p>Також прохання не тримати код неактивованим, це значно ускладнює нашу роботу і може
                                    привести до помилок - спонсор може віддати код комусь іншому, бо бачить його як
                                    активний у себе. Як тільки у вас буде можливість/зв'язок - зареєструйтеся будь
                                    ласка.</p>
                            </div>
                            <textarea id="text-copy" hidden="hidden"></textarea>

                            <div class="modal-footer">
                                <span onclick="javascipt:copyText();" class="btn btn-warning">
                                    Copy text
                                </span>
                                <button id="reload" type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(function () {
            const textInvite = `Вітаємо!
                                 Ваш інвайт код:
                                 >>>> :code <<<<
                                 Android: https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1
                                 iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199
                                 Desktop: https://bsky.app
                                 Гайди по блускай, та кого пофоловити можна знайти тут: https://faq.uabluerail.org
                                 Якщо ваша ласка, напишіть нам коли успішно зареєструєтесь ваш нік в системі для звітності.
                                 Ваш спонсор:
                                 :giver
                                 Також прохання не тримати код неактивованим, це значно ускладнює нашу роботу і може привести до помилок - спонсор може віддати код комусь іншому, бо бачить його як активний у себе. Як тільки у вас буде можливість/зв'язок - зареєструйтеся будь ласка.
                            `
            window.copyText = function () {
                let copyText = document.getElementById("text-copy");
                copyText.select();
                copyText.setSelectionRange(0, 99999); // For mobile devices
                navigator.clipboard.writeText(copyText.value);
                alert('Текст скопійовано');
                return false;
            }

            $('input').change(() => {
                $('#donate').prop('disabled', !$('input:checked').length);
            });
            $('.request-button-book').on('click', event => {
                let current = $(event.target);
                let id = current.data('id');
                let code = current.data('code');
                let handle = current.data('handle');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/book/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function () {
                        $("#code").html(code);
                        $("#giver").html(handle);
                        let text = $("#text-copy");
                        text.val(textInvite.replace(':code', code).replace(':giver', handle));
                        new bootstrap.Modal(document.getElementById('myModal'), {
                            keyboard: false
                        }).toggle();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
            $('.request-button-forget').on('click', event => {
                let current = $(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/forget/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
            $('.request-button-unbook').on('click', event => {
                let current = $(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/unbook/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
            $('.request-button-text').on('click', event => {
                let current = $(event.target);
                let code = current.data('code');
                let handle = current.data('handle');
                let text = $("#text-copy");
                $("#code").html(code);
                $("#giver").html(handle);
                text.val(textInvite.replace(':code', code).replace(':giver', handle));
                new bootstrap.Modal(document.getElementById('myModal'), {
                    keyboard: false
                }).toggle();
            });
            $('#reload').on('click', event => {
                window.location.reload();
            });
        });
    </script>
@endsection
