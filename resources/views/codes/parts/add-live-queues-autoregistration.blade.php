<form method="POST" action="{{ route('invite-add') }}">
@csrf
    <div class="row">
        <div class="col-md-4 p-3">
            <div class="form-check">
                <input id="email" type="text" class="form-control" name="email"
                       value="" placeholder="email" required>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="form-check">
                <input id="username" type="text" class="form-control" name="username"
                       value="" placeholder="username" required>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="form-check">
                <input id="username" type="text" class="form-control" name="username"
                       value="" placeholder="password" required>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="form-check input-group">
                <input id="link" type="text" class="form-control" name="link"
                       value="" placeholder="Посилання" required autofocus>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="form-check">
                <select id="train" class="form-select form-select-md mb-1"
                        aria-label=".form-select-md"
                        name="train" required>
                    @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                        <option
                            value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="form-check input-group">
                <div style="align-items:center; justify-content: center; display:flex;">
                    <button type="submit" class="btn btn-outline-danger">
                        В кінець черги 🔚
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="accordion mt-2" id="accordionHelpAddLiveQueues">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHelpAddLiveQueues">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#helpAddLiveQueues"
                    aria-expanded="false"
                    aria-controls="helpAddLiveQueues">
                Довідка по розділу "Для автореєстрації"
            </button>
        </h2>
        <div id="helpAddLiveQueues" class="accordion-collapse collapse"
             aria-labelledby="headingHelpAddLiveQueues"
             data-bs-parent="#helpAddLiveQueues">
            <div class="accordion-body">
                <p class="lead">
                    Цей розділ має формочку, яка дозволяє додати карточку в "Живі черги 🚶🚶🚶"
                    для тих хто погодився на автореєстрацію. Всі поля обов'язкові. Людина попадає в звичайну чергу, но
                    бот може ії зарєєструвати, і тоді ваша карточка в черзі буде мати інформацію щодо реєстрації.
                    В поле посилання краще вставляти реальне посилання, щоб в розділі
                    вище посилання було клікабельне. Можно просто написати текст, тоді посилання просто
                    буде "бите". Якщо посилання нормальне, то по кліку воно нормально відпрацює.
                    Обираємо чергу куди закинути в кінець черги картоку і нкнопку "В кінець черги 🔚"
                    Карточка з'явиться в розділі "Живі черги 🚶🚶🚶"
                </p>
            </div>
        </div>
    </div>
</div>
