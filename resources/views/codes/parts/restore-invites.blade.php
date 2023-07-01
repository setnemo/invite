<?php

$items = \App\Models\Invite::query()->withTrashed()->whereNotNull('deleted_at')->orderBy('deleted_at', 'desc')->get();
?>
@if(!$items->isEmpty())
    <div class="table-responsive">

        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">Посилання</th>
                <th scope="col">Потяг</th>
                <th scope="col">Видалив</th>
                <th scope="col">Дати</th>
                <th scope="col">Відновити/Знищити</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items->all() as $item)
                <tr>
                    <td>
                        {{ $item->link }}
                        @if($ai = $item->autoInvite()->withTrashed()->first())
                            <hr>
                            <span data-id="{{ $item->id }}"
                                  class="btn btn-xs request-button-show-auto-invite-trashed
                                      @if($ai->done && $ai->successful)
                                      btn-success
                                      @elseif($ai->done)
                                      btn-warning
                                      @else
                                      btn-primary
                                      @endif
                                      "
                                  title="Подивитися Auto Invite">
                                <i data-id="{{ $item->id }}" class="fa fa-eye" aria-hidden="true"></i> Статус автоінвайту</span>
                            <hr>
                        @endif
                    </td>
                    <td>{{ \App\Models\InviteCode::TRAIN_MAP[$item->train_number] ?? '' }}</td>
                    <td><a href="https://bsky.app/profile/{{ $item->remover_did }}">{{ $item->remover_handle }}</a>
                        ({{ $item->remover_email }})
                    </td>
                    <td>
                        <div class="row">
                            <span class="nobr text-success">created: {{ $item->created_at }}</span>
                            <span class="nobr text-danger">deleted: {{ $item->deleted_at }}</span>
                            <span class="nobr text-secondary">updated: {{ $item->updated_at }}</span>
                        </div>
                    </td>
                    <td>
                        <button data-id="{{ $item->id }}" class="btn btn-success request-button-invite-restore">
                            <i data-id="{{ $item->id }}" class="fa fa-share" aria-hidden="true"></i>
                        </button>
                        <button data-id="{{ $item->id }}" class="btn btn-danger request-button-invite-force-delete">
                            <i data-id="{{ $item->id }}" class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div id="copyTextAutoTrashedModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Статус автоматичної реєстрації</h5>
                </div>
                <div class="modal-body">
                    <p>Посилання: <span id="text-trashed-link"></span></p>
                    <p>Потяг: <span id="text-trashed-train"></span></p>
                    <p>email: <span id="text-trashed-email"></span></p>
                    <p>username: <span id="text-trashed-username"></span></p>
                    <p>password: <span id="text-trashed-password"></span></p>
                    <p>Бот намагався зареєструвати: <span id="text-trashed-done"></span></p>
                    <p>Зареєстровано: <span id="text-trashed-successful"></span></p>
                    <p>Результат: <span id="text-trashed-response"></span></p>
                </div>
                <textarea id="text-trashed-auto-copy" hidden="hidden"></textarea>
                <div class="modal-footer">
                    <button id="copyTextAutoTrashed" onclick="javascipt:copyText();" class="btn btn-warning">
                        Текст для відправки
                    </button>
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $(function () {
            const textSuccessTrashed = `Вітаємо!
Вас успішно зареєстровано!

Android: https://play.google.com/store/apps/details?id=xyz.blueskyweb.app&hl=en_US&pli=1

iOS: https://apps.apple.com/us/app/bluesky-social/id6444370199

Desktop: https://bsky.app

Ваш логін: :login
Ваш пароль: :password
Ваш email: :email

Нагадуємо що інвайти які ви будете отримувати ви можете подарувати на сайті https://invite.bluesky.co.ua та підтримати українськи спільноти.

Дякуємо!
`
            window.copyText = function () {
                let copyText = document.getElementById("text-trashed-auto-copy");
                copyText.select();
                copyText.setSelectionRange(0, 99999); // For mobile devices
                navigator.clipboard.writeText(copyText.value);
                alert('Текст скопійовано');
                return false;
            }
            $('.request-button-show-auto-invite-trashed').on('click', event => {
                let current = $(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'GET',
                    url: `{{ route('welcome') }}/invite/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        console.log(data);
                        $("#text-trashed-link").html(data.link);
                        $("#text-trashed-train").html(data.train_number);
                        $("#text-trashed-email").html(data.auto_invite.email);
                        $("#text-trashed-username").html(data.auto_invite.username);
                        $("#text-trashed-password").html(data.auto_invite.password);
                        $("#text-trashed-done").html(data.auto_invite.done ? 'Так' : 'Ні');
                        $("#text-trashed-successful").html(data.auto_invite.successful ? 'Так' : 'Ні');
                        $("#text-trashed-response").html(data.auto_invite.response);
                        let success = $("#text-trashed-auto-copy");
                        success.val(textSuccessTrashed.replace(':login', data.auto_invite.username).replace(':password', data.auto_invite.password).replace(':email', data.auto_invite.email));
                        if (data.auto_invite.successful !== true) {
                            $('#copyTextAutoTrashed').attr('disabled', 'disabled');
                        }
                        new bootstrap.Modal(document.getElementById('copyTextAutoTrashedModal'), {
                            keyboard: false
                        }).toggle();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
            $('.request-button-invite-restore').on('click', event => {
                if (!confirm('Відновити місце в черзі?')) {
                    return;
                }
                let current = $(event.target);
                console.log(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/invite-restore/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        console.log(data);
                        window.location.reload();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
            $('.request-button-invite-force-delete').on('click', event => {
                if (!confirm('Видалити з черги?')) {
                    return;
                }
                if (!confirm('Це буде неможливо відновити. Ви впевнені?')) {
                    return;
                }
                let current = $(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/invite-force-delete/` + id,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        console.log(data);
                        window.location.reload();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
            });
        });
    </script>
@endif
<div class="accordion mt-2" id="accordionHelpRestoreInvites">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpRestoreInvites">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpRestoreInvites"
                    aria-expanded="false"
                    aria-controls="helpRestoreInvites">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpRestoreInvites" class="accordion-collapse collapse"
             aria-labelledby="headingHelpRestoreInvites"
             data-bs-parent="#helpRestoreInvites">
            <div class="accordion-body">
                <p class="lead">
                    Таблиця яка дозволяє відновити інвайти, які видалено кнопкою "Забути"
                    в розділі "Живі черги 🚶🚶🚶". Також там можно побачити хто "забув" інвайт з кондукторів. Є кнопка
                    безповоротньо видалити код з бази даних. Зробив це тільки для тестів, коли руками накидаємо інвайтів
                    і треба буде потім почистити.
                </p>
            </div>
        </div>
    </div>
</div>
