<form method="POST" action="{{ route('invite-add') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 p-3">
            <div class="form-check input-group">
            <input id="link" type="text" class="form-control" name="link"
                   value="" placeholder="Посилання" required autofocus>
        </div>
        </div>
        <div class="col-md-3 p-3">
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
        <div class="col-md-3 p-3">
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
