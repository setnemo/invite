<form method="POST" action="{{ route('donate') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 p-3">
            <div class="form-check input-group">
                <input  type="text" class="form-control"
                        value=""
                       id="customCode"
                       name="code"
                       placeholder="Робочій інвайт код"
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
                        class="btn btn-danger">
                    Віддати жебракам
                </button>
            </div>
        </div>
    </div>

</form>
