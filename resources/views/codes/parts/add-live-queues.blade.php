<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-usual-tab" data-bs-toggle="pill" data-bs-target="#pills-usual"
                type="button" role="tab" aria-controls="pills-usual" aria-selected="true">Звичайна форма
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-autoregistrations-tab" data-bs-toggle="pill"
                data-bs-target="#pills-autoregistrations" type="button" role="tab"
                aria-controls="pills-autoregistrations" aria-selected="false">Для автореєстрації
        </button>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-usual" role="tabpanel" aria-labelledby="pills-usual-tab">
{{--        @include('codes.parts.add-live-queues-usual')--}}
    </div>

    <div class="tab-pane fade" id="pills-autoregistrations" role="tabpanel"
         aria-labelledby="pills-autoregistrations-tab">
{{--        @include('codes.parts.add-live-queues-autoregistration')--}}
    </div>
</div>

