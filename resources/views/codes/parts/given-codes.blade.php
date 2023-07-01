<?php

$currentIt = 0; ?>
@foreach($trains as $name => $items)
    <div class="accordion-item">
        <h2 class="accordion-header" id="givenCodes">
            <button class="accordion-button{{ !$currentIt ? '' : ' collapsed' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#moderateCodes{{ md5($name) }}"
                    aria-expanded="{{ !$currentIt ? 'true' : 'false' }}"
                    aria-controls="moderateCodes{{ md5($name) }}">
                    <?php
                    $booked = $free = 0;
                    foreach ($items as $item) {
                        if ($item->booked_at) {
                            $booked++;
                        } else {
                            $free++;
                        }
                    }
                    ?>
                    @if($free)
                        <span class="badge rounded-pill bg-success m-1">{{ $free }}</span>
                    @endif
                    @if($booked)
                        <span class="badge rounded-pill bg-warning m-1">{{ $booked }}</span>
                    @endif
                {{ $name }}
            </button>
        </h2>
        <div id="moderateCodes{{ md5($name) }}" class="accordion-collapse collapse {{ !$currentIt++ ? 'show' : '' }}"
             aria-labelledby="givenCodes"
             data-bs-parent="#moderateCodes{{ md5($name) }}">
            <div class="accordion-body">
                <div class="row p-3">
                    @foreach($items as $item)
                        <div class="col-md-4 border border-success m-1">
                            <hr>
                            Дарує <a href="https://bsky.app/profile/{{ $item->giver_did }}"
                                     target="_blank">{{ $item->giver_handle }}</a>
                            <hr>
                            Надано {{ $item->created_at }}
                            <hr>
                            @if($item->booked_at)
                                Заброньовано користувачем <a href="https://bsky.app/profile/{{ $item->recipient_did }}"
                                                             target="_blank">{{ $item->recipient_handle }}</a>
                                <hr>
                                <span data-id="{{ $item->id }}" class="btn btn-xs btn-success request-button-unbook"
                                      data-code="{{ $item->code }}"
                                      data-handle='https://bsky.app/profile/{{ $item->giver_did }}'
                                      title="Повернути Invite Code 🎟️">
                                <i class="fa fa-share" aria-hidden="true"></i> Повернути 🎟️ в доступні
                            </span>
                                <hr>
                                <span data-id="{{ $item->id }}" class="btn btn-xs btn-danger request-button-forget"
                                      data-code="{{ $item->code }}"
                                      data-handle='https://bsky.app/profile/{{ $item->giver_did }}'
                                      title="Видалити Invite Code 🎟️">
                                <i class="fa fa-times" aria-hidden="true"></i> Видалити 🎟️ (Код використано або додано помилково)
                            </span>
                                <hr>
                                <span data-id="{{ $item->id }}" class="btn btn-xs btn-primary request-button-text"
                                      data-code="{{ $item->code }}"
                                      data-handle='https://bsky.app/profile/{{ $item->giver_did }}'
                                      title="Подивитися Invite Code 🎟️">
                                <i class="fa fa-eye" aria-hidden="true"></i> Подивитись
                            </span>
                            @else
                                <span data-id="{{ $item->id }}" class="btn btn-xs btn-warning request-button-book"
                                      data-code="{{ $item->code }}"
                                      data-handle='https://bsky.app/profile/{{ $item->giver_did }}'
                                      title="Забронювати Invite Code 🎟️">
                                <i class="fa fa-book" aria-hidden="true"></i> Забронювати 🎟️
                            </span>
                            @endif
                            <hr>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
<div id="copyTextModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Інвайт-код 🎟️ заброньовано!</h5>
            </div>
            <div class="modal-body">
                <p>Вітаємо!</p>
                <p>Ваш інвайт-код:</p>
                <p>>>>> <span id="code"></span> <<<<</p>
                <p>Android:
                    https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1</p>
                <p>iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199</p>
                <p>Desktop: https://bsky.app</p>
                <p>Гайди по блускай, та кого пофоловити можна знайти тут:
                    https://faq.uabluerail.org</p>
                <p>Якщо ваша ласка, напишіть нам коли успішно зареєструєтесь ваш нік в системі
                    для
                    звітності.</p>
                <p>Ваш спонсор:</p>
                <p><span id="giver"></span></p>
                <p>Також прохання не тримати код неактивованим, це значно ускладнює нашу роботу
                    і може привести до помилок - спонсор може віддати код комусь іншому, бо бачить його
                    як активний у себе. Як тільки у вас буде можливість/зв'язок - зареєструйтеся
                    будь ласка. І не забуть сказати що ви це зробили.</p>
            </div>
            <textarea id="text-copy" hidden="hidden"></textarea>
            <div class="modal-footer">
                                <span onclick="javascipt:copyText();" class="btn btn-warning">
                                    Текст для відправки
                                </span>
                <button id="reload" type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script type="module">
    $(function () {
        const textInvite = `Вітаємо!
Ваш інвайт-код:
>>>> :code <<<<

Android: https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1

iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199

Desktop: https://bsky.app

Гайди по блускай, та кого пофоловити можна знайти тут: https://faq.uabluerail.org
Якщо ваша ласка, напишіть нам коли успішно зареєструєтесь ваш нік в системі для звітності.
Ваш спонсор: :giver

Також прохання не тримати код неактивованим, це значно ускладнює нашу роботу і може привести до помилок - спонсор може віддати код комусь іншому, бо бачить його як активний у себе. Як тільки у вас буде можливість/зв'язок - зареєструйтеся будь ласка. І не забуть сказати що ви це зробили.
`
        window.copyText = function () {
            let copyText = document.getElementById("text-copy");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);
            alert('Текст скопійовано');
            return false;
        }
        $('.request-button-book').on('click', event => {
            let current = $(event.target);
            let id = current.data('id');
            let code = current.data('code');
            let handle = current.data('handle');
            $.ajax({
                type: 'POST',
                url: `{{ route('welcome') }}/book/` + id,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    recipient_handle: `{{ $handle }}`,
                    recipient_email: `{{ $account['email'] ?? '' }}`,
                    recipient_did: `{{ $account['did'] ?? '' }}`
                },
                success: function () {
                    $("#code").html(code);
                    $("#giver").html(handle);
                    let text = $("#text-copy");
                    text.val(textInvite.replace(':code', code).replace(':giver', handle));
                    new bootstrap.Modal(document.getElementById('copyTextModal'), {
                        keyboard: false
                    }).toggle();
                },
                error: function (result) {
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
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    remover_handle: `{{ $handle }}`,
                    remover_email: `{{ $account['email'] ?? '' }}`,
                    remover_did: `{{ $account['did'] ?? '' }}`
                },
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
            new bootstrap.Modal(document.getElementById('copyTextModal'), {
                keyboard: false
            }).toggle();
        });
        $('#reload').on('click', event => {
            window.location.reload();
        });
    });
</script>

<div class="accordion mt-2" id="accordionHelpGivenCodes">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpGivenCodes">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpGivenCodes"
                    aria-expanded="false"
                    aria-controls="helpGivenCodes">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpGivenCodes" class="accordion-collapse collapse"
             aria-labelledby="headingHelpGivenCodes"
             data-bs-parent="#helpGivenCodes">
            <div class="accordion-body">
                <p class="lead">
                    Розшифровка кольору бейджів в назві
                </p>
                <ul>
                    <li>зелений: в черзі є вілні інвайт-коди</li>
                    <li>жовтиий: в черзі є заброньовані інвайт-коди</li>
                </ul>
                <p class="lead">
                    В залежності від налаштувань провідник може бачити кілька черг
                    Блок черги має в тайтлі коло з кольором
                </p>
                <ul>
                    <li>жовтим кількість кодів, які кондуктори взяли в роботу</li>
                    <li>зеленим кількість кодів, які кондуктори можуть взяти в роботу</li>
                </ul>
                <p class="lead">
                    Коди при наявності сортуються від старих до нових. В блоці коду ми можемо побачити:
                </p>
                <ul>
                    <li>хто дарує з посиланням на блюскай</li>
                    <li>коли код додали до системи (час серверу там, по Грінвічу)</li>
                    <li>кнопка "Забронювати 🎟️"</li>
                </ul>
                <p class="lead">
                    При натискані кнопки "Забронювати 🎟️" автоматично з'явиться окно з текстом коду
                    та текстом який треба відправити людині. Для зручності знизу є
                    кнопка скопіювати текст. При натисканні закрити сторінка оновиться.
                    Сайт швидкий, якщо у вас норм інтернет, ви даже не помітете оновлення,
                    но блок коду, де ви натиснули "Забронювати 🎟️" зміниться. Ця кнопка
                    пропаде і з'являться три інших:
                </p>
                <ul>
                    <li>Повернути 🎟️ в доступні</li>
                    <li>Видалити 🎟️ (Код використано або додано помилково)</li>
                    <li>Подивитись</li>
                </ul>
                <p class="lead">
                    "Повернути 🎟️ в доступні" просто верне код в чергу і він знову буде доступний.
                    "Видалити 🎟️ (Код використано або додано помилково)" умовно видалить код, но
                    суперадмін може його відновити за потреби. Якщо ви випадково натиснули, там
                    треба ще додатково потім натиснути ОК, і можно відминити дію кнопкою Cancel.
                    "Подивитись" кнопка покаже віконечко з текстом, яке було коли ви тільки
                    натиснули "Забронювати 🎟️"
                </p>
            </div>
        </div>
    </div>
</div>
