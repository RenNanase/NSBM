@extends('layout')

@section('title', 'Emergency Department Dashboard - NSBM')
@section('header', 'Emergency Department Dashboard')

@section('content')


<!-- Emergency Department Main Features -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Infectious Disease Card -->
    {{-- <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Infectious Disease</h3>

        <div class="p-4 bg-pink-50 rounded-lg border border-pink-100 mb-4">
            <h4 class="font-medium text-pink-800 mb-3 text-center">Today's Entries</h4>
            <div class="grid grid-cols-4 gap-3">
                <i class="fas fa-calendar-day mr-1"></i> {{ now()->format('d M Y') }}
            </div>
        </div>
    </div> --}}
</div>

@endsection
