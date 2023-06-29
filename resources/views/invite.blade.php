@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Стати прихожанином Церкви Святого Інвайту</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('invite-add') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="link" class="col-md-4 col-form-label text-md-end">
                                    Посилання для зв'язку, твітур там, тг
                                </label>
                                <div class="col-md-6">
                                    <input id="link" type="text" class="form-control" name="link"
                                           value="" required autofocus>
                                </div>
                            </div>
                            <div class="row mb-0 p-3">
                                <label for="train" class="col-md-4 col-form-label text-md-end">
                                    Оберіть свій потяг
                                </label>
                                <div class="col-md-6">
                                    <select id="train" class="form-select form-select-md mb-1" aria-label=".form-select-md"
                                            name="train" required>
                                        @foreach(\App\Models\InviteCode::TRAIN_MAP as $id => $name)
                                            <option
                                                value="{{ $id }} {{ isset(\App\Models\InviteCode::TRAIN_DISABLED[$id]) ? 'disabled' : '' }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="align-items:center; justify-content: center; display:flex;">
                                    <button type="submit" class="btn btn-primary">
                                        Амінь
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
