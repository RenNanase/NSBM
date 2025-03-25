@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold" style="color: var(--color-text-primary);">Add New Infectious Disease Record</h3>
        <div>
            <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm font-medium" style="color: var(--color-text-primary); border-color: var(--color-border); background-color: var(--color-bg-alt);">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <div class="dashboard-card p-6 mb-6">
        <form action="{{ route('infectious-diseases.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="disease" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Disease Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-virus" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="disease" name="disease" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">Select Disease</option>
                            @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                                <option value="{{ $disease }}" {{ old('disease') == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('disease')
                        <p class="mt-1 text-sm" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="patient_type" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Patient Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user-injured" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="patient_type" name="patient_type" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">Select Patient Type</option>
                            @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                                <option value="{{ $type }}" {{ old('patient_type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('patient_type')
                        <p class="mt-1 text-sm" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ward_id" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Ward</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hospital-alt" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="ward_id" name="ward_id" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('ward_id')
                        <p class="mt-1 text-sm" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Total Cases</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hashtag" style="color: var(--color-text-light);"></i>
                        </div>
                        <input type="number" name="total" id="total" min="0" value="{{ old('total', 0) }}" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                    </div>
                    @error('total')
                        <p class="mt-1 text-sm" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Notes</label>
                <div class="relative">
                    <textarea id="notes" name="notes" rows="3" class="block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);" placeholder="Any additional information about this case...">{{ old('notes') }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-sm" style="color: var(--color-accent);">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium" style="color: var(--color-text-primary); border-color: var(--color-border); background-color: var(--color-bg-alt);">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white">
                    <i class="fas fa-save mr-2"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
