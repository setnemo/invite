<?php
$currentIt = 0; ?>
@foreach($queues as $name => $items)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button{{ !$currentIt ? '' : ' collapsed' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#moderateQueues{{ md5($name) }}"
                    aria-expanded="{{ !$currentIt ? 'true' : 'false' }}"
                    aria-controls="moderateQueues{{ md5($name) }}">
                {{ $name }}
            </button>
        </h2>
        <div id="moderateQueues{{ md5($name) }}" class="accordion-collapse collapse {{ !$currentIt++ ? 'show' : '' }}"
             aria-labelledby="headingOne"
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
                            <i class="fa fa-times" aria-hidden="true"></i> Забути
                        </span>
                            <hr>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
