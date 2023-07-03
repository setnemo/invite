<form method="POST" action="{{ route('invite-move') }}">
    @csrf
    <div class="row">
        <div class="col-md-3 p-3">
            <div class="form-check">
                <select class="form-select form-select-md mb-0"
                        aria-label=".form-select-md"
                        name="train_from" required>
                    @foreach(\App\Models\InviteCode::SELECT_MAP as $id => $name)
                        <option
                            value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">
                            З {{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check input-group">
                <input type="number" class="form-control"
                       value="1"
                       id="quantity"
                       name="quantity"
                       required>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check">
                <select class="form-select form-select-md mb-0"
                        aria-label=".form-select-md"
                        name="train_to" required>
                    @foreach(\App\Models\InviteCode::SELECT_MAP as $id => $name)
                        <option
                            value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">
                            На {{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check input-group">
                <button id="donateCustom" type="submit"
                        class="btn btn-outline-danger">
                    Перекинути інвайт-коди 🎟️
                </button>
            </div>
        </div>
    </div>
</form>
<div class="accordion mt-2" id="accordionHelpMoveCodes">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpMoveCodes">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpMoveCodes"
                    aria-expanded="false"
                    aria-controls="helpMoveCodes">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpMoveCodes" class="accordion-collapse collapse"
             aria-labelledby="headingHelpMoveCodes"
             data-bs-parent="#helpMoveCodes">
            <div class="accordion-body">
                <p class="lead">
                    Тут все просто, вибирається з якого потягу в який, та кількість. Чисто для зручності коли
                    одна черга має багато кодів, і треба поділитися
                </p>
            </div>
        </div>
    </div>
</div>
