@extends('layout')

@section('title', 'Add Daily Data Entry - NSBM')
@section('header', 'Add Daily Data Entry')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('daily-data.index') }}" class="text-secondary hover:text-secondary-dark">
            <i class="fas fa-arrow-left mr-2"></i>Back to Daily Data List
        </a>
    </div>

    <form action="{{ route('daily-data.store') }}" method="POST" class="daily-data-form">
        @csrf

        @if(session('error'))
            <div class="bg-accent-light text-accent-dark p-4 rounded-md mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="form-group">
                <label for="date" class="block mb-2" style="color: var(--color-text-primary);">Date</label>
                <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-md focus:outline-none @error('date') border-accent @enderror"
                       style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                @error('date')
                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                @enderror

                @if(isset($existingEntry))
                    <p class="text-accent mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        There is already an entry for this date.
                        <a href="{{ route('daily-data.edit', $existingEntry->id) }}" class="underline">Edit existing entry</a>
                    </p>
                @endif
            </div>

            <div class="form-group">
                <label for="ward_id" class="block mb-2" style="color: var(--color-text-primary);">Ward</label>
                @if(isset($ward) && $ward)
                    <div class="p-3 border rounded-md" style="background-color: var(--color-bg-alt); border-color: var(--color-border); color: var(--color-text-primary);">
                        {{ $ward->name }}
                        <input type="hidden" name="ward_id" value="{{ $ward->id }}">
                    </div>
                @else
                    <select id="ward_id" name="ward_id"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none @error('ward_id') border-accent @enderror"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                        <option value="">-- Select a Ward --</option>
                        @foreach($wards as $ward)
                            <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                                {{ $ward->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('ward_id')
                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-primary-light p-4 rounded-md mb-6" style="border: 1px solid var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Daily Health Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="death" class="block mb-2" style="color: var(--color-text-primary);">Deaths</label>
                    <input type="number" id="death" name="death" min="0" value="{{ old('death', 0) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('death') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('death')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neonatal_jaundice" class="block mb-2" style="color: var(--color-text-primary);">Neonatal Jaundice</label>
                    <input type="number" id="neonatal_jaundice" name="neonatal_jaundice" min="0" value="{{ old('neonatal_jaundice', 0) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('neonatal_jaundice') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('neonatal_jaundice')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bedridden_case" class="block mb-2" style="color: var(--color-text-primary);">Bedridden Cases</label>
                    <input type="number" id="bedridden_case" name="bedridden_case" min="0" value="{{ old('bedridden_case', 0) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('bedridden_case') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('bedridden_case')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="incident_report" class="block mb-2" style="color: var(--color-text-primary);">Incident Reports</label>
                    <input type="number" id="incident_report" name="incident_report" min="0" value="{{ old('incident_report', 0) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('incident_report') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('incident_report')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-6">
            <label for="remarks" class="block mb-2" style="color: var(--color-text-primary);">Remarks (Optional)</label>
            <textarea id="remarks" name="remarks" rows="4"
                      class="w-full px-4 py-2 border rounded-md focus:outline-none @error('remarks') border-accent @enderror"
                      style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">{{ old('remarks') }}</textarea>
            @error('remarks')
                <p class="text-accent text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('daily-data.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> Save Entry
            </button>
        </div>
    </form>
</div>
@endsection
