<form method="POST" action="{{ route('donate') }}">
    @csrf
    <div class="row">
        <div class="col-md-3 p-3">
            <div class="form-check input-group">
                <input type="text" class="form-control"
                       value=""
                       id="customHandle"
                       name="handle"
                       placeholder="Юзернейм без @"
                       required>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check input-group">
                <input type="text" class="form-control"
                       value=""
                       id="customCode"
                       name="code"
                       placeholder="Робочій інвайт-код"
                       required>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check">
                <select class="form-select form-select-md mb-0"
                        aria-label=".form-select-md"
                        name="train" required>
                    @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                        <option
                            value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 p-3">
            <div class="form-check input-group">
                <button id="donateCustom" type="submit"
                        class="btn btn-outline-danger">
                    Записати інвайт-код 🎟️
                </button>
            </div>
        </div>
    </div>
</form>
<div class="accordion mt-2" id="accordionHelpAddCodes">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpAddCodes">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpAddCodes"
                    aria-expanded="false"
                    aria-controls="helpAddCodes">
                Довідка по розділу
            </button>
        </h2>
        <div id="helpAddCodes" class="accordion-collapse collapse"
             aria-labelledby="headingHelpAddCodes"
             data-bs-parent="#helpAddCodes">
            <div class="accordion-body">
                <p class="lead">
                    Додати інвайт-код 🎟️ вручну це форма, яка дозволяє покласти в базу робочий
                    інвайт-код. Ця форма має наступні поля
                </p>
                <ul>
                    <li>Юзернейм без @</li>
                    <li>Робочій інвайт-код</li>
                    <li>вибрати потяг куди його відправити</li>
                    <li> кнопку "Записати інвайт-код 🎟️"</li>
                </ul>
                <p class="lead">
                    Коли ви натискаєте кнопку "Записати інвайт-код 🎟️" автоматично робиться запит в блюскай
                    по хендлу щоб забрати унікальний айді користувача, який дарує цей код і який ви вручну
                    забиваете в систему. Кожний користувач має такий, і всі посилання на юзерів блю ская я роблю
                    саме за таким кодом, бо користувач може змінити хендл, а цей ідентифікатор ніколи не змінюється
                    Це робиться, щоб потім не було битих посилань, якщо користувач подарував код самостійно (не ви
                    руцями а він зайшов на сайт) і після цього змінив свій хендл. Щоб ми могли знайти його потім.
                    Ще я в базі зберігаю імейл користувача, його можно побачити якщо людина сама дарує код. В цьому
                    випадку коли ви руцями додаете код ми його імейл не побачимо по хендлу, тому я впишу імейл
                    кондуктора. Після цього код падає в чергу
                </p>
            </div>
        </div>
    </div>
</div>
