@extends('layout')

@section('title', 'Emergency Room BOR Details - NSBM')
@section('header', 'Emergency Room BOR Details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR List
        </a>
    </div>

    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <h2 class="text-2xl font-semibold text-blue-800">
            BOR Entry for {{ \Carbon\Carbon::parse($borEntry->date)->format('d M Y') }} - {{ $borEntry->shift }} Shift
        </h2>
        <p class="text-sm text-blue-600 mt-1">
            Recorded by {{ $borEntry->user->username }} on {{ $borEntry->created_at->format('d M Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Patient Categories</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-gray-700">Green (Non-Urgent)</span>
                    </span>
                    <span class="font-medium text-green-600">{{ $borEntry->green }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                        <span class="text-gray-700">Yellow (Urgent)</span>
                    </span>
                    <span class="font-medium text-yellow-600">{{ $borEntry->yellow }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-gray-700">Red (Emergency)</span>
                    </span>
                    <span class="font-medium text-red-600">{{ $borEntry->red }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="font-semibold text-gray-800">Shift Total</span>
                    <span class="font-bold text-blue-600">{{ $borEntry->grand_total }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="font-semibold text-gray-800">24 Hours Census</span>
                    <span class="font-bold text-indigo-600">{{ $borEntry->hours_24_census }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4 border-b pb-2">Patient Outcomes</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Ambulance Calls</span>
                    <span class="font-medium">{{ $borEntry->ambulance_call }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Admissions</span>
                    <span class="font-medium">{{ $borEntry->admission }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Transfers</span>
                    <span class="font-medium">{{ $borEntry->transfer }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Deaths</span>
                    <span class="font-medium">{{ $borEntry->death }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($borEntry->remarks))
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3 border-b pb-2">Remarks</h3>
        <div class="bg-gray-50 p-4 rounded-md">
            <p class="text-gray-700">{{ $borEntry->remarks }}</p>
        </div>
    </div>
    @endif

    <div class="flex justify-between mt-8">
        <a href="{{ route('emergency.bor.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md">
            Back to List
        </a>
        <div class="flex space-x-4">
            <a href="{{ route('emergency.bor.edit', $borEntry->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                Edit Entry
            </a>
            <form action="{{ route('emergency.bor.destroy', $borEntry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md">
                    Delete Entry
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
