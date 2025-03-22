@extends('layout')

@section('title', 'Add Emergency Room BOR Entry - NSBM')
@section('header', 'Add Emergency Room BOR Entry')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR List
        </a>
    </div>

    <form action="{{ route('emergency.bor.store') }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($availableShifts && count($availableShifts) > 0)
            <div class="mb-8 bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-2">New Entry for {{ $today->format('d M Y') }}</h3>
                <p class="text-sm text-blue-600">
                    You're creating a new entry for today. Please select a shift and fill in the patient counts.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" id="date" value="{{ $today->format('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="shift" class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                    <select name="shift" id="shift"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Shift</option>
                        @foreach($availableShifts as $shift)
                            <option value="{{ $shift }}">{{ $shift }}</option>
                        @endforeach
                    </select>
                    @error('shift')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Patient Categories</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                        <label for="green" class="block text-sm font-medium text-green-800 mb-1">Green (Non-Urgent)</label>
                        <input type="number" name="green" id="green" value="{{ old('green', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('green')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                        <label for="yellow" class="block text-sm font-medium text-yellow-800 mb-1">Yellow (Urgent)</label>
                        <input type="number" name="yellow" id="yellow" value="{{ old('yellow', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                        @error('yellow')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <label for="red" class="block text-sm font-medium text-red-800 mb-1">Red (Emergency)</label>
                        <input type="number" name="red" id="red" value="{{ old('red', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        @error('red')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Patient Outcomes</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="ambulance_call" class="block text-sm font-medium text-gray-700 mb-1">Ambulance Calls</label>
                        <input type="number" name="ambulance_call" id="ambulance_call" value="{{ old('ambulance_call', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('ambulance_call')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admission" class="block text-sm font-medium text-gray-700 mb-1">Admissions</label>
                        <input type="number" name="admission" id="admission" value="{{ old('admission', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('admission')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="transfer" class="block text-sm font-medium text-gray-700 mb-1">Transfers</label>
                        <input type="number" name="transfer" id="transfer" value="{{ old('transfer', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('transfer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="death" class="block text-sm font-medium text-gray-700 mb-1">Deaths</label>
                        <input type="number" name="death" id="death" value="{{ old('death', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('death')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" id="remarks" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Any additional notes or comments...">{{ old('remarks') }}</textarea>
                @error('remarks')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                    Save Entry
                </button>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                <h3 class="font-medium">All shifts for today have been recorded</h3>
                <p class="mt-1">You have already recorded BOR data for all shifts today. To make changes, please edit the existing entries.</p>
                <div class="mt-3">
                    <a href="{{ route('emergency.bor.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md mt-2 inline-block">
                        View Today's Entries
                    </a>
                </div>
            </div>
        @endif
    </form>
</div>
@endsection
