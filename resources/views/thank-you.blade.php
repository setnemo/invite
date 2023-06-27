@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login Information</div>

                    <div class="card-body">
                            <div class="row mb-3">
                                <p>{{ session()->get('acc') }}</p>
                                <p>{{ session()->get('codes') }}</p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
