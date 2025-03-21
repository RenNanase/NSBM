@extends('layout')

@section('title', 'Delivery Details - NSBM')
@section('header', 'Delivery Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium">{{ $ward->name }}</h3>
                <p class="text-sm text-gray-600">Delivery report for {{ $delivery->report_date->format('M d, Y') }}</p>
                <p class="text-xs text-gray-500">Created by {{ $delivery->user->username }} on {{ $delivery->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Left column -->
        <div>
            <h3 class="text-lg font-medium mb-4">Primary Deliveries</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">SVD:</span>
                    <span class="text-lg">{{ $delivery->svd }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">LSCS:</span>
                    <span class="text-lg">{{ $delivery->lscs }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Vacuum:</span>
                    <span class="text-lg">{{ $delivery->vacuum }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Forceps:</span>
                    <span class="text-lg">{{ $delivery->forceps }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Breech:</span>
                    <span class="text-lg">{{ $delivery->breech }}</span>
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div>
            <h3 class="text-lg font-medium mb-4">Other Deliveries</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Eclampsia:</span>
                    <span class="text-lg">{{ $delivery->eclampsia }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Twin:</span>
                    <span class="text-lg">{{ $delivery->twin }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">MRP:</span>
                    <span class="text-lg">{{ $delivery->mrp }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">FSB/MBS:</span>
                    <span class="text-lg">{{ $delivery->fsb_mbs }}</span>
                </div>
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">BBA:</span>
                    <span class="text-lg">{{ $delivery->bba }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total -->
    <div class="border-t border-gray-200 pt-6 mb-6">
        <div class="flex justify-between items-center px-6 py-4 bg-blue-50 rounded-lg">
            <span class="font-semibold text-xl">Total Deliveries:</span>
            <span class="text-2xl font-bold text-blue-700">{{ $delivery->total }}</span>
        </div>
    </div>

    <!-- Notes -->
    @if($delivery->notes)
    <div class="border-t border-gray-200 pt-6 mb-6">
        <h3 class="text-lg font-medium mb-3">Additional Notes</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-700 whitespace-pre-line">{{ $delivery->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mt-8">
        <a href="{{ route('delivery.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition text-gray-700">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
        <div class="flex space-x-3">
            <a href="{{ route('delivery.edit', $delivery) }}" class="px-4 py-2 bg-amber-100 text-amber-800 hover:bg-amber-200 rounded-md transition">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            @if(Auth::user()->username === 'admin')
            <form action="{{ route('delivery.destroy', $delivery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-800 hover:bg-red-200 rounded-md transition">
                    <i class="fas fa-trash-alt mr-1"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
