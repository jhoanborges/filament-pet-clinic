@extends('layouts.app')

@section('content')
    <div class="flex flex-col min-h-screen items-center justify-center"> 
        <div class="mt-0 mb-0 bg-white shadow rounded-lg">
            @livewire(\App\Livewire\PosComponent::class)
        </div>
    </div>
@endsection