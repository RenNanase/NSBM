@extends('layout')

@section('title', 'Select Ward - NSBM')
@section('header', 'Select Ward')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="max-w-md w-full login-card p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold" style="color: var(--color-secondary);">NSBM</h1>
            <p class="text-gray-600">Nursing Service Bed Management</p>
        </div>

        @if($wards->isEmpty())
            <div class="p-4 rounded-md mb-4" style="background-color: var(--color-primary-light); border: 1px solid var(--color-border); color: var(--color-accent);">
                No wards available. Please contact the administrator.
            </div>
        @else
            <form action="{{ route('ward.select.post') }}" method="POST" id="wardSelectionForm">
                @csrf

                {{-- <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Legend:</p>
                    <div class="flex items-center mb-1">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm">Wards you have access to</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span class="text-sm">Wards you don't have access to</span>
                    </div>
                </div> --}}

                <div class="mb-6">
                    <label for="ward_id" class="block text-gray-700 font-medium mb-2 text-sm">Wards & Department</label>
                    <select name="ward_id" id="ward_id" class="w-full text-sm px-4 py-3 rounded-md input-field focus:outline-none" required>
                        <option value="">-- Select Ward/Department --</option>
                        @foreach($wards as $ward)
                            @php
                                $hasAccess = in_array($ward->id, $userWards);
                                $optionClass = $hasAccess ? 'text-pink-700 font-medium' : 'text-gray-500';
                                $accessIndicator = $hasAccess ? '⭐ ' : ''; // Star
                                $wardData = json_encode(['id' => $ward->id, 'hasAccess' => $hasAccess]);
                            @endphp
                            <option value="{{ $ward->id }}" class="{{ $optionClass }}" data-ward="{{ $wardData }}">
                                {{ $accessIndicator }}{{ $ward->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('ward_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full btn-login text-white py-3 px-4 rounded-md transition duration-200 font-semibold">
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
                    alert('You do not have access to this ward. Please contact the IT Department.');
                }
            }
        });
    }
});
</script>

<style>
    .login-card {
        background-color: var(--color-primary-light, white);
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--color-border, #ffccd5);
    }

    .btn-login {
        background-color: var(--color-secondary, #f78fa7);
        transition: all 0.2s;
    }

    .btn-login:hover {
        background-color: var(--color-secondary-dark, #c57285);
    }

    .input-field {
        background-color: white;
        border: 1px solid var(--color-border, #ffccd5);
    }

    .input-field:focus {
        border-color: var(--color-secondary, #f78fa7);
        outline: none;
        box-shadow: 0 0 0 2px rgba(247, 143, 167, 0.2);
    }
</style>
@endsection
