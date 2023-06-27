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
