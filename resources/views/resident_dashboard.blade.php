@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h2 class="mb-3">Resident Dashboard</h2>
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <p>You are logged in as a <strong>resident</strong>.</p>
            <!-- Add resident-specific content here -->
        </div>
    </div>
</div>
@endsection