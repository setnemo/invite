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
