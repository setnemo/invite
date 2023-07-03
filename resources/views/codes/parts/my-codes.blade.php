@if(!empty($codes))
    <form method="POST" action="{{ route('donate') }}">
        @csrf
        <div class="row mb-3 p-3">
            @foreach($codes as $code)
                <div class="col-md-3"
                     style="{{ !empty($code['uses']) && !in_array($code['code'], $addedCodes) && !in_array($code['code'], $deletedCodes) ? 'display: none' : '' }}">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="{{ $code['code'] }}"
                               name="{{ $code['code'] }}" {{ !empty($code['uses']) || (in_array($code['code'], $addedCodes)) ? 'disabled' : '' }}>
                        <label class="form-check-label" for="{{ $code['code'] }}">
                            {!! app(\App\Services\CodeService::class)->getCodeCheckbox($code) !!}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mb-0 p-3">
            <label for="train"
                   class="col-md-4 col-form-label text-md-end">
                Оберіть поїзд (спільноту), на яку пожертвувати код:
            </label>
            <div class="col-md-4">
                <select id="train"
                        class="form-select form-select-md mb-1"
                        aria-label=".form-select-md"
                        name="train" required>
                    @foreach(\App\Models\InviteCode::SELECT_MAP as $id => $name)
                        <option
                            value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div style="align-items:center; justify-content: center; display:flex;">
                    <button id="donate" type="submit" class="btn btn-danger"
                            disabled>
                        Пожертвувати інвайт-код 🎟️
                    </button>
                </div>
            </div>
        </div>
    </form>
@endif


<script type="module">
    $(function () {
        $('input').change(() => {
            $('#donate').prop('disabled', !$('input:checked').length);
        });
    });
</script>

<div class="accordion mt-2" id="accordionHelpMyCodes">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpMyCodes">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpMyCodes"
                    aria-expanded="false"
                    aria-controls="helpMyCodes">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpMyCodes" class="accordion-collapse collapse"
             aria-labelledby="headingHelpMyCodes"
             data-bs-parent="#helpMyCodes">
            <div class="accordion-body">
                <p class="lead">
                    Розділ показує які коди, у вас є (доступні і не доступні), які сайт побачив в Вашому аккаунті
                    BlueSky
                </p>
                <ul>
                    <li>блактиний: Код додано до системи, дякуємо</li>
                    <li>помаранчевий: Код раніше вже додавався до системи</li>
                    <li>червоний: Код недійсний :(</li>
                    <li>зелений: Код використано, дякуємо!</li>
                    <li>бірюзовий: Код доступний для пожертви</li>
                </ul>
                <p class="lead">
                    Для пожертви коду в систему треба обрати його галкою, обрати потяг в який ви хочете його
                    пожертвувати,
                    і натиснути "Пожертвувати інвайт-код 🎟️"
                </p>
            </div>
        </div>
    </div>
</div>
