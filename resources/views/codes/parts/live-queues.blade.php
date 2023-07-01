<?php

$currentIt = 0; ?>
@foreach($queues as $name => $items)
    <div class="accordion-item">
        <h2 class="accordion-header" id="moderateQueues">
            <button class="accordion-button{{ !$currentIt ? '' : ' collapsed' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#moderateQueues{{ md5($name) }}"
                    aria-expanded="{{ !$currentIt ? 'true' : 'false' }}"
                    aria-controls="moderateQueues{{ md5($name) }}">
                    <?php
                    $autoFinishDone = $autoFinish = $without = 0;
                    foreach ($items as $item) {
                        if ($item->autoInvite && $item->autoInvite->done) {
                            $autoFinishDone++;
                        } elseif ($item->autoInvite) {
                            $autoFinish++;
                        } else {
                            $without++;
                        }
                    }
                    ?>
                @if($autoFinishDone)
                    <span class="badge rounded-pill bg-danger m-1">{{ $autoFinishDone }}</span>
                @endif
                @if($autoFinish)
                    <span class="badge rounded-pill bg-success m-1">{{ $autoFinish }}</span>
                @endif
                @if($without)
                    <span class="badge rounded-pill bg-warning m-1">{{ $without }}</span>
                @endif
                {{ $name }}
            </button>
        </h2>
        <div id="moderateQueues{{ md5($name) }}" class="accordion-collapse collapse {{ !$currentIt++ ? 'show' : '' }}"
             aria-labelledby="moderateQueues"
             data-bs-parent="#moderateQueues{{ md5($name) }}">
            <div class="accordion-body">
                <div class="row p-3">
                    @foreach($items as $queueNumber => $item)
                        <div class="col-md-2 border border-success m-1">
                            <hr>
                            Посилання: <a href="{{ $item->link }}"
                                          target="_blank">{{ $item->link }}</a>
                            <hr>
                            Дата {{ $item->created_at }}, номер в
                            черзі: {{ $queueNumber + 1 }}
                            <hr>
                            <span data-id="{{ $item->id }}"
                                  class="btn btn-xs btn-danger request-button-forget-invite"
                                  title="Забути Invite">
                            <i data-id="{{ $item->id }}" class="fa fa-times" aria-hidden="true"></i> Забути</span>
                            <hr>
                            @if($item->autoInvite)
                                <span data-id="{{ $item->id }}"
                                      class="btn btn-xs request-button-show-auto-invite
                                      @if($item->autoInvite->done && $item->autoInvite->successful)
                                      btn-success
                                      @elseif($item->autoInvite->done)
                                      btn-warning
                                      @else
                                      btn-primary
                                      @endif
                                      "
                                      title="Подивитися Auto Invite">
                                <i data-id="{{ $item->id }}" class="fa fa-eye" aria-hidden="true"></i> Статус автоінвайту</span>
                                <hr>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="accordion mt-2" id="accordionHelpLiveQueues">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpLiveQueues">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpLiveQueues"
                    aria-expanded="false"
                    aria-controls="helpLiveQueues">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpLiveQueues" class="accordion-collapse collapse"
             aria-labelledby="headingHelpLiveQueues"
             data-bs-parent="#helpLiveQueues">
            <div class="accordion-body">
                <p class="lead">
                    Цей розділ показує черги, які провідники самостійно додали на сайт.
                    Кожен потяг бачить тільки свою, якщо провідник на кілька черг - бачить тільки свої.
                    Цей блок показує карточки черги, яка має в собі наступні поля
                </p>
                <ul>
                    <li>Посилання (клікабельне)</li>
                    <li>Дата коли додали (час по Грінвчічу) та номер в черзі</li>
                    <li>кнопку "Забути" (супер адмін може відновити за потреби)</li>
                </ul>
                <p class="lead">
                    Цей розділ суто для зручності обліку, наповнювати його можно в наступнопу розділі
                </p>
            </div>
        </div>
    </div>
</div>
<div id="copyTextAutoModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Статус автоматичної реєстрації</h5>
            </div>
            <div class="modal-body">
                <p>Посилання: <span id="text-link"></span></p>
                <p>Потяг: <span id="text-train"></span></p>
                <p>email: <span id="text-email"></span></p>
                <p>username: <span id="text-username"></span></p>
                <p>password: <span id="text-password"></span></p>
                <p>Бот намагався зареєструвати: <span id="text-done"></span></p>
                <p>Зареєстровано: <span id="text-successful"></span></p>
                <p>Результат: <span id="text-response"></span></p>
            </div>
            <textarea id="text-auto-copy" hidden="hidden"></textarea>
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
        const textSuccess = `Вітаємо!
Вас успішно зареєстровано!

Android: https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1

iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199

Desktop: https://bsky.app

Ваш логін: :login
Ваш логін: :password
Ваш логін: :email

Нагадуємо що інвайти які ви будете отримувати ви можете подарувати на сайті https://invite.bluesky.co.ua та підтримати українськи спільноти.

Дякуємо!
`
        window.copyText = function () {
            let copyText = document.getElementById("text-auto-copy");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);
            alert('Текст скопійовано');
            return false;
        }

        $('.request-button-show-auto-invite').on('click', event => {
            let current = $(event.target);
            let id = current.data('id');
            $.ajax({
                type: 'GET',
                url: `{{ route('welcome') }}/invite/` + id,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    console.log(data);
                    $("#text-link").html(data.link);
                    $("#text-train").html(data.train);
                    $("#text-email").html(data.auto_invite.email);
                    $("#text-username").html(data.auto_invite.username);
                    $("#text-password").html(data.auto_invite.password);
                    $("#text-done").html(data.auto_invite.done ? 'Так' : 'Ні');
                    $("#text-successful").html(data.auto_invite.successful ? 'Так' : 'Ні');
                    $("#text-response").html(data.auto_invite.response);
                    let success = $("#text-auto-copy");
                    success.val(textSuccess.replace(':login', data.auto_invite.username).replace(':password', data.auto_invite.password).replace(':email', data.auto_invite.email));
                    if (!data.auto_invite.successful) {
                        success.attr('disabled', 'disabled');
                    }
                    new bootstrap.Modal(document.getElementById('copyTextAutoModal'), {
                        keyboard: false
                    }).toggle();
                },
                error: function (result) {
                    alert('error');
                }
            });
        });
    });
</script>
