@extends('front.layouts.app')

@section('content')
    <section class="section-9 pt-5">
        <div class="container">
        <div class="row">
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! Session::get('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
            </div>
            @endif
        </div>
        <div class="card">
            <div class="card-body d-flex justify-content-center align-items-center">
                <h4>Thank You</h4>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('customJs')
    
@endsection