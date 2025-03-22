@extends('layout')

@section('title', 'Edit Emergency Room BOR Entry - NSBM')
@section('header', 'Edit Emergency Room BOR Entry')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR List
        </a>
    </div>

    <form action="{{ route('emergency.bor.update', $borEntry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-8 bg-blue-50 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-blue-800 mb-2">Editing Entry for {{ \Carbon\Carbon::parse($borEntry->date)->format('d M Y') }}</h3>
            <p class="text-sm text-blue-600">
                Shift: <span class="font-medium">{{ $borEntry->shift }}</span>
            </p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Patient Categories</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <label for="green" class="block text-sm font-medium text-green-800 mb-1">Green (Non-Urgent)</label>
                    <input type="number" name="green" id="green" value="{{ old('green', $borEntry->green) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    @error('green')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                    <label for="yellow" class="block text-sm font-medium text-yellow-800 mb-1">Yellow (Urgent)</label>
                    <input type="number" name="yellow" id="yellow" value="{{ old('yellow', $borEntry->yellow) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                    @error('yellow')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                    <label for="red" class="block text-sm font-medium text-red-800 mb-1">Red (Emergency)</label>
                    <input type="number" name="red" id="red" value="{{ old('red', $borEntry->red) }}" min="0"
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
                    <input type="number" name="ambulance_call" id="ambulance_call" value="{{ old('ambulance_call', $borEntry->ambulance_call) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('ambulance_call')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="admission" class="block text-sm font-medium text-gray-700 mb-1">Admissions</label>
                    <input type="number" name="admission" id="admission" value="{{ old('admission', $borEntry->admission) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('admission')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transfer" class="block text-sm font-medium text-gray-700 mb-1">Transfers</label>
                    <input type="number" name="transfer" id="transfer" value="{{ old('transfer', $borEntry->transfer) }}" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('transfer')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="death" class="block text-sm font-medium text-gray-700 mb-1">Deaths</label>
                    <input type="number" name="death" id="death" value="{{ old('death', $borEntry->death) }}" min="0"
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
                placeholder="Any additional notes or comments...">{{ old('remarks', $borEntry->remarks) }}</textarea>
            @error('remarks')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('emergency.bor.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md">
                Cancel
            </a>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                Update Entry
            </button>
        </div>
    </form>
</div>
@endsection
