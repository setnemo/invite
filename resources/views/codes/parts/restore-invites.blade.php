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
                    <td>{{ $item->link }}</td>
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
    <script type="module">
        $(function () {
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
