@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profil Saya</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label>Member Sejak</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->created_at->format('d M Y') }}" readonly>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection