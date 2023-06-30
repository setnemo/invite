@extends('layouts.app')

@section('content')
<?php
$account     = json_decode(session()->get('acc', '{}'), true);
$data        = json_decode(session()->get('codes', '{}'), true);
$codes       = $data['codes'] ?? [];
$handle      = $account['handle'] ?? '';
$trains      = \App\Models\InviteCode::getCodesByHandle($handle);
$addedCodes  = \App\Models\InviteCode::query()->get()->pluck('code')->toArray();
$bookedCodes  = \App\Models\InviteCode::query()->whereNotNull('booked_at')->get()->pluck('code')->toArray();
$deletedCodes   = \App\Models\InviteCode::query()->withTrashed()->get()->pluck('code')->toArray();
$bookedAndDeletedCodes  = \App\Models\InviteCode::query()->whereNotNull('booked_at')->withTrashed()->get()->pluck('code')->toArray();
$allBookedCodes = array_merge($bookedCodes, $bookedAndDeletedCodes);
$canAddCodes = in_array($handle, \App\Models\InviteCode::CAN_ADD_CODES);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            @if($canAddCodes)
            <div class="card mb-2">
                <div class="card-header">Консоль адміністратора. Тут ви можете додати код 🎟️ вручну.</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('donate') }}">
                        @csrf
                        <div class="row p-3">
                            <div class="form-check">
                                <select class="form-select form-select-md mb-0" aria-label=".form-select-md" name="train" required>
                                    @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                                    <option value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row col-md-12 p-3">
                            <div class="form-check input-group">
                                <input class="form-check-text mb-12" type="text" value="" id="customCode" name="code" required>
                                <button id="donateCustom" type="submit" class="btn btn-danger">
                                    Записати інвайт-код 🎟️
                                </button>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="form-check">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            <div class="card mb-2">
                <div class="card-header">Мої інвайт-коди 🎟️:</div>
                <div class="card-body">
                    @if(!empty($codes))
                    <form method="POST" action="{{ route('donate') }}">
                        @csrf
                        <div class="row mb-3 p-3">
                            @foreach($codes as $code)
                            <div class="col-md-3" style="{{ !empty($code['uses']) && !in_array($code['code'], $addedCodes) && !in_array($code['code'], $deletedCodes) ? 'display: none' : '' }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="{{ $code['code'] }}" name="{{ $code['code'] }}" {{ !empty($code['uses']) || (in_array($code['code'], $addedCodes)) ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="{{ $code['code'] }}">
                                        @if (in_array($code['code'], $addedCodes) && empty($code['uses']))
                                        <span class="text-primary" data-bs-placement="top" data-bs-html="true" title="Код додано до системи, дякуємо">
                                            @elseif (in_array($code['code'], $deletedCodes) && empty($code['uses']))
                                            <span class="text-warning" data-bs-placement="top" data-bs-html="true" title="Код раніше вже додавався до системи">
                                                @elseif (in_array($code['code'], $addedCodes) && !empty($code['uses']))
                                                <span class="text-danger" data-bs-placement="top" data-bs-html="true" title="Код недійсний :(">
                                                    @elseif (in_array($code['code'], $allBookedCodes) && !empty($code['uses']))
                                                    <span class="text-success" data-bs-placement="top" data-bs-html="true" title="Код використано, дякуємо!">
                                                        @elseif (empty($code['uses']))
                                                        <span class="text-info" data-bs-placement="top" data-bs-html="true" title="Код доступний для пожертви">
                                                            @endif

                                                            {{ $code['code'] }}

                                                            @if (in_array($code['code'], $addedCodes) && empty($code['uses'])) (код додано до системи, дякуємо)
                                                            @elseif (in_array($code['code'], $deletedCodes) && empty($code['uses'])) (код було видалено з системи)
                                                            @elseif (in_array($code['code'], $addedCodes) && !empty($code['uses'])) (код недійсний)
                                                            @elseif (in_array($code['code'], $allBookedCodes) && !empty($code['uses'])) (код використано, дякуємо!)
                                                            @elseif (empty($code['uses'])) (код доступний для пожертви)
                                                            @endif

                                                            @if (empty($code['uses']))
                                                        </span>
                                                        @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <label for="train" class="col-md-4 col-form-label text-lg-start">
                            Оберіть поїзд (спільноту), на яку пожертвувати код:
                        </label>
                        <div class="row mb-0 p-3">
                            <select class="form-select form-select-lg mb-1" aria-label=".form-select-xs" name="train" required>
                                @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                                <option value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div style="align-items:center; justify-content: center; display:flex;">
                                <button id="donate" type="submit" class="btn btn-danger" disabled>
                                    Пожертвувати інвайт-код 🎟️
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
                            Дарує <a href="https://bsky.app/profile/{{ $item->giver_did }}" target="_blank">{{ $item->giver_handle }}</a>
                            <hr>
                            Надано {{ $item->created_at }}
                            <hr>
                            @if($item->booked_at)
                            Заброньовано користувачем <a href="https://bsky.app/profile/{{ $item->recipient_did }}" target="_blank">{{ $item->recipient_handle }}</a>
                            <hr>
                            <span data-id="{{ $item->id }}" class="btn btn-xs btn-success request-button-unbook" data-code="{{ $item->code }}" data-handle='https://bsky.app/profile/{{ $item->giver_did }}' title="Повернути Invite Code 🎟️">
                                <i class="fa fa-share" aria-hidden="true"></i> Повернути 🎟️ в доступні
                            </span>
                            <hr>
                            <span data-id="{{ $item->id }}" class="btn btn-xs btn-danger request-button-forget" data-code="{{ $item->code }}" data-handle='https://bsky.app/profile/{{ $item->giver_did }}' title="Видалити Invite Code 🎟️">
                                <i class="fa fa-times" aria-hidden="true"></i> Видалити 🎟️ (Код використано або додано помилково)
                            </span>
                            <hr>
                            <span data-id="{{ $item->id }}" class="btn btn-xs btn-primary request-button-text" data-code="{{ $item->code }}" data-handle='https://bsky.app/profile/{{ $item->giver_did }}' title="Подивитися Invite Code 🎟️">
                                <i class="fa fa-eye" aria-hidden="true"></i> Подивитись
                            </span>
                            @else
                            <span data-id="{{ $item->id }}" class="btn btn-xs btn-warning request-button-book" data-code="{{ $item->code }}" data-handle='https://bsky.app/profile/{{ $item->giver_did }}' title="Забронювати Invite Code 🎟️">
                                <i class="fa fa-book" aria-hidden="true"></i> Забронювати 🎟️
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
                            <h5 class="modal-title">Код заброньовано!</h5>
                        </div>
                        <div class="modal-body">
                            <p>Вітаємо!</p>
                            <p>Ваш інвайт код:</p>
                            <p>>>>> <span id="code"></span>
                                <<<<< /p>
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
                                        ласка. І не забуть сказати що ви це зробили.</p>
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
    $(function() {
        const textInvite = `Вітаємо!
Ваш інвайт код:
>>>> :code <<<<

Android: https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1

iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199

Desktop: https://bsky.app

Гайди по блускай, та кого пофоловити можна знайти тут: https://faq.uabluerail.org
Якщо ваша ласка, напишіть нам коли успішно зареєструєтесь ваш нік в системі для звітності.
Ваш спонсор: :giver

Також прохання не тримати код неактивованим, це значно ускладнює нашу роботу і може привести до помилок - спонсор може віддати код комусь іншому, бо бачить його як активний у себе. Як тільки у вас буде можливість/зв'язок - зареєструйтеся будь ласка. І не забуть сказати що ви це зробили.
`
        window.copyText = function() {
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    recipient_handle: `{{ $handle }}`,
                    recipient_email: `{{ $account['email'] ?? '' }}`,
                    recipient_did: `{{ $account['did'] ?? '' }}`
                },
                success: function() {
                    $("#code").html(code);
                    $("#giver").html(handle);
                    let text = $("#text-copy");
                    text.val(textInvite.replace(':code', code).replace(':giver', handle));
                    new bootstrap.Modal(document.getElementById('myModal'), {
                        keyboard: false
                    }).toggle();
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
        $('.request-button-forget').on('click', event => {
            if (!confirm('Забути код?')) {
                return;
            }
            let current = $(event.target);
            let id = current.data('id');
            $.ajax({
                type: 'POST',
                url: `{{ route('welcome') }}/forget/` + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    remover_handle: `{{ $handle }}`,
                    remover_email: `{{ $account['email'] ?? '' }}`,
                    remover_did: `{{ $account['did'] ?? '' }}`
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(result) {
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(result) {
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