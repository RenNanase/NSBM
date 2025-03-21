@extends('layout')

@section('title', 'Add Delivery Record - NSBM')
@section('header', 'Add Delivery Record')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center">
            <div class="bg-pink-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium">{{ $ward->name }}</h3>
                <p class="text-sm text-gray-600">Create a new delivery record</p>
            </div>
        </div>
    </div>

    <form action="{{ route('delivery.store') }}" method="POST">
        @csrf

        <!-- Date Selection -->
        <div class="mb-6">
            <label for="report_date" class="block text-gray-700 mb-2">Report Date</label>
            <input type="date" name="report_date" id="report_date"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                   value="{{ old('report_date', now()->format('Y-m-d')) }}" required>
            <p class="text-xs text-gray-500 mt-1">
                Date for this delivery report
            </p>
            @error('report_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Delivery Types Section -->
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-medium mb-4">Delivery Types</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="svd" class="block text-gray-700 mb-2">SVD</label>
                    <input type="number" name="svd" id="svd"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('svd', 0) }}" required min="0">
                    @error('svd')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lscs" class="block text-gray-700 mb-2">LSCS</label>
                    <input type="number" name="lscs" id="lscs"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('lscs', 0) }}" required min="0">
                    @error('lscs')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="vacuum" class="block text-gray-700 mb-2">Vacuum</label>
                    <input type="number" name="vacuum" id="vacuum"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('vacuum', 0) }}" required min="0">
                    @error('vacuum')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="forceps" class="block text-gray-700 mb-2">Forceps</label>
                    <input type="number" name="forceps" id="forceps"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('forceps', 0) }}" required min="0">
                    @error('forceps')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="breech" class="block text-gray-700 mb-2">Breech</label>
                    <input type="number" name="breech" id="breech"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('breech', 0) }}" required min="0">
                    @error('breech')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="eclampsia" class="block text-gray-700 mb-2">Eclampsia</label>
                    <input type="number" name="eclampsia" id="eclampsia"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('eclampsia', 0) }}" required min="0">
                    @error('eclampsia')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="twin" class="block text-gray-700 mb-2">Twin</label>
                    <input type="number" name="twin" id="twin"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('twin', 0) }}" required min="0">
                    @error('twin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mrp" class="block text-gray-700 mb-2">MRP</label>
                    <input type="number" name="mrp" id="mrp"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('mrp', 0) }}" required min="0">
                    @error('mrp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fsb_mbs" class="block text-gray-700 mb-2">FSB/MBS</label>
                    <input type="number" name="fsb_mbs" id="fsb_mbs"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('fsb_mbs', 0) }}" required min="0">
                    @error('fsb_mbs')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bba" class="block text-gray-700 mb-2">BBA</label>
                    <input type="number" name="bba" id="bba"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('bba', 0) }}" required min="0">
                    @error('bba')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="mb-6">
            <label for="notes" class="block text-gray-700 mb-2">Additional Notes</label>
            <textarea name="notes" id="notes" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('notes') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">
                Any additional information about these deliveries
            </p>
            @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Info Section -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h4 class="font-medium text-blue-800 mb-2">Information</h4>
            <p class="text-sm text-blue-700">
                The total count of deliveries will be calculated automatically. Please ensure that all entries are correct before submitting.
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('delivery.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition text-gray-700">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-md transition">
                Save Record
            </button>
        </div>
    </form>
</div>

<!-- JavaScript for automatically calculating total -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // No need to calculate total here as it's done on the server side
});
</script>
@endsection
