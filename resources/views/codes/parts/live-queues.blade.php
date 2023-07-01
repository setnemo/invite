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
                <span class="badge rounded-pill bg-danger m-1">{{ count($items) }}</span>
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
