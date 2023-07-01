<?php

$items = \App\Models\InviteCode::query()->withTrashed()->whereNotNull('deleted_at')->orderBy('deleted_at', 'desc')->get(
);
?>
@if(!$items->isEmpty())
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">інвайт-код 🎟️</th>
                <th scope="col">Потяг</th>
                <th scope="col">Власник</th>
                <th scope="col">Кондуктор</th>
                <th scope="col">Видалив</th>
                <th scope="col">Дати</th>
                <th scope="col">Відновити/Знищити</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items->all() as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ \App\Models\InviteCode::TRAIN_MAP[$item->train_number] ?? '' }}</td>
                    <td><a href="https://bsky.app/profile/{{ $item->giver_did }}">{{ $item->giver_handle }}</a>
                        ({{ $item->giver_email }})
                    </td>
                    <td><a href="https://bsky.app/profile/{{ $item->recipient_did }}">{{ $item->recipient_handle }}</a>
                        ({{ $item->recipient_email }})
                    </td>
                    <td><a href="https://bsky.app/profile/{{ $item->remover_did }}">{{ $item->remover_handle }}</a>
                        ({{ $item->remover_email }})
                    </td>
                    <td>
                        <div class="row">
                            <span class="nobr text-success">created: {{ $item->created_at }}</span>
                            <span class="nobr text-warning">booked: {{ $item->booked_at }}</span>
                            <span class="nobr text-danger">deleted: {{ $item->deleted_at }}</span>
                            <span class="nobr text-secondary">updated: {{ $item->updated_at }}</span>
                        </div>
                    </td>
                    <td>
                        <button data-id="{{ $item->id }}" class="btn btn-success request-button-codes-restore">
                            <i data-id="{{ $item->id }}" class="fa fa-share" aria-hidden="true"></i>
                        </button>
                        <button data-id="{{ $item->id }}" class="btn btn-danger request-button-codes-force-delete">
                            <i data-id="{{ $item->id }}" class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script type="module">
        $(function () {
            $('.request-button-codes-restore').on('click', event => {
                if (!confirm('Відновити код?')) {
                    return;
                }
                let current = $(event.target);
                console.log(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/invite-code-restore/` + id,
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
            $('.request-button-codes-force-delete').on('click', event => {
                if (!confirm('Видалити код?')) {
                    return;
                }
                if (!confirm('Код буде неможливо відновити. Ви впевнені?')) {
                    return;
                }
                let current = $(event.target);
                let id = current.data('id');
                $.ajax({
                    type: 'POST',
                    url: `{{ route('welcome') }}/invite-code-force-delete/` + id,
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
        });
    </script>
@endif
<div class="accordion mt-2" id="accordionHelpRestoreCodes">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpRestoreCodes">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpRestoreCodes"
                    aria-expanded="false"
                    aria-controls="helpRestoreCodes">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpRestoreCodes" class="accordion-collapse collapse"
             aria-labelledby="headingHelpRestoreCodes"
             data-bs-parent="#helpRestoreCodes">
            <div class="accordion-body">
                <p class="lead">
                    Таблиця яка дозволяє відновити інвайт-коди, які видалено кнопкою "Видалити 🎟️ (Код використано або додано помилково)"
                    в розділі "Доступні інвайт-коди 🎟️ для роздачі". Також там можно побачити хто подарував код,
                    хто його бронював з кондукторів і їто натиснув "Забути". Є кнопка безповоротньо видалити код з бази даних.
                    Зробив це тільки для тестів, коли руками накидаємо кодів і треба буде потім почистити.
                </p>
            </div>
        </div>
    </div>
</div>
