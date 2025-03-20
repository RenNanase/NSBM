@extends('layout')

@section('title', 'Select Ward - NSBM')
@section('header', 'Select Ward')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 font-['Noto_Sans_JP'] uppercase mb-2">NSBM</h1>
            <p class="text-gray-600">Nursing Service Bed Management</p>
        </div>

        @if($wards->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-4">
                No wards available. Please contact the administrator.
            </div>
        @else
            <form action="{{ route('ward.select.post') }}" method="POST" id="wardSelectionForm">
                @csrf

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Legend:</p>
                    <div class="flex items-center mb-1">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm">Wards you have access to</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span class="text-sm">Wards you don't have access to</span>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="ward_id" class="block text-gray-700 font-medium mb-2">Available Wards</label>
                    <select name="ward_id" id="ward_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors" required>
                        <option value="">-- Select Ward --</option>
                        @foreach($wards as $ward)
                            @php
                                $hasAccess = in_array($ward->id, $userWards);
                                $optionClass = $hasAccess ? 'text-green-700 font-medium' : 'text-gray-500';
                                $accessIndicator = $hasAccess ? '✓ ' : '';
                                $wardData = json_encode(['id' => $ward->id, 'hasAccess' => $hasAccess]);
                            @endphp
                            <option value="{{ $ward->id }}" class="{{ $optionClass }}" data-ward="{{ $wardData }}">
                                {{ $accessIndicator }}{{ $ward->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('ward_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-3 px-4 rounded-md font-medium transition duration-200 transform hover:translate-y-[-2px]">
                    Continue
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('logout') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wardSelector = document.getElementById('ward_id');
    const form = document.getElementById('wardSelectionForm');

    if (form && wardSelector) {
        form.addEventListener('submit', function(e) {
            if (wardSelector.value) {
                const selectedOption = wardSelector.options[wardSelector.selectedIndex];
                const wardData = JSON.parse(selectedOption.getAttribute('data-ward'));

                if (!wardData.hasAccess) {
                    e.preventDefault();
                    alert('You do not have access to this ward. Please contact the administrator if you need access.');
                }
            }
        });
    }
});
</script>
@endsection
