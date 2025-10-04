@if (auth()->user()->isAdmin())
    <x-app-layout>
        @include('backend.orders._content-show')
    </x-app-layout>
@else
    @extends('frontend.layouts.main')

    @section('content')
        @include('backend.orders._content-show')
    @endsection
@endif
