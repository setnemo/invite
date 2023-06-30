@foreach($queues as $name => $items)
    <div class="card mt-2">
        <div class="card-header">Черга {{ $name }}</div>
        <div class="card-body">
            <div class="row mb-3 p-3">
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
                            <i class="fa fa-times" aria-hidden="true"></i> Забути
                        </span>
                        <hr>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
