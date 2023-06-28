@extends('layouts.app')

@section('content')
    <?php
    $rawCodes = session()->get('codes', '{}');
    $data     = json_decode($rawCodes, true);
    $codes    = $data['codes'] ?? [];
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Мої інвайт коди</div>

                    <div class="card-body">
                        @if(!empty($codes))
                            <form method="POST" action="{{ route('donate') }}">
                                @csrf
                                <div class="row mb-3 p-3">
                                    @foreach($codes as $code)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="{{ $code['code'] }}"
                                                       name="{{ $code['code'] }}" {{ !empty($code['uses']) ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $code['code'] }}">
                                                    {{ $code['code'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mb-0 p-3">
                                    <select class="form-select form-select-lg mb-1" aria-label=".form-select-xs"
                                            name="train">
                                        <option selected>На який потяг?</option>
                                        <option value="1">№1 "Церква Святого Інвайту"</option>
                                        <option value="5">№5 "Військовий"</option>
                                        <option value="6">№6 "Qırım"</option>
                                        <option value="7">№7 "Волонтерський"</option>
                                        <option value="8">№8 "Укркрафт"</option>
                                        <option value="9" disabled>№9 "Жіноче купе"</option>
                                        <option value="10" disabled>№10 "Великий Ненацьк"</option>
                                        <option value="11">№11 "Украрт"</option>
                                        <option value="12">№12 "Укррайт"</option>
                                        <option value="14">№14 "Укркосплей"</option>
                                        <option value="15">№15 "Блоґерский"</option>
                                        <option value="16">№16 "Розіграші"</option>
                                    </select>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button id="donate" type="submit" class="btn btn-dark" disabled>Віддати жебракам</button> (це поки що не працює)
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $(() => {
            $('input').change(() => {
                $('#donate').prop('disabled', !$('input:checked').length);
            });
        });
    </script>
@endsection
