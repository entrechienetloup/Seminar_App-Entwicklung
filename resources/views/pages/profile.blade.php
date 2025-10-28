@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h1 class="h4 mb-4">Mein Profil</h1>

            <dl class="row mb-4">
                <dt class="col-4 text-muted">Name</dt>
                <dd class="col-8">{{ $user->name }}</dd>


                <dt class="col-4 text-muted">Rolle</dt>
                <dd class="col-8">
                    <span class="badge 
                        @if($user->role === 'leiter') bg-primary-subtle text-primary-emphasis
                        @elseif($user->role === 'techniker') bg-success-subtle text-success-emphasis
                        @else bg-secondary-subtle text-secondary-emphasis
                        @endif
                    text-capitalize">
                        {{ $user->role }}
                    </span>
                </dd>

            </dl>
            <div class="d-flex justify-content-between mt-4">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">
            Abmelden
        </button>
    </form>
</div>

    </div>
</div>
@endsection
