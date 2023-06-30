<form method="POST" action="{{ route('invite-move') }}">
    @csrf
    <div class="row">
        <div class="col-md-3 p-3">
            <div class="form-check">
                <select class="form-select form-select-md mb-0"
                        aria-label=".form-select-md"
                        name="train_from" required>
                    @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
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
                    @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
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
