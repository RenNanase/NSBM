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
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="{{ old('date', $today->format('Y-m-d')) }}" class="@error('date') border-accent @enderror">
                @error('date')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="ward_id">Select Ward</label>
                <select id="ward_id" name="ward_id" class="@error('ward_id') border-accent @enderror">
                    <option value="">-- Select a Ward --</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                            {{ $ward->name }}
                        </option>
                    @endforeach
                </select>
                @error('ward_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-primary-light p-4 rounded-md mb-6" style="border: 1px solid var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Daily Health Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="death">Deaths</label>
                    <input type="number" id="death" name="death" min="0" value="{{ old('death', 0) }}" class="@error('death') border-accent @enderror">
                    @error('death')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neonatal_jaundice">Neonatal Jaundice</label>
                    <input type="number" id="neonatal_jaundice" name="neonatal_jaundice" min="0" value="{{ old('neonatal_jaundice', 0) }}" class="@error('neonatal_jaundice') border-accent @enderror">
                    @error('neonatal_jaundice')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bedridden_case">Bedridden Cases</label>
                    <input type="number" id="bedridden_case" name="bedridden_case" min="0" value="{{ old('bedridden_case', 0) }}" class="@error('bedridden_case') border-accent @enderror">
                    @error('bedridden_case')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="incident_report">Incident Reports</label>
                    <input type="number" id="incident_report" name="incident_report" min="0" value="{{ old('incident_report', 0) }}" class="@error('incident_report') border-accent @enderror">
                    @error('incident_report')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-6">
            <label for="remarks">Remarks (Optional)</label>
            <textarea id="remarks" name="remarks" rows="4" class="@error('remarks') border-accent @enderror">{{ old('remarks') }}</textarea>
            @error('remarks')
                <p class="error-message">{{ $message }}</p>
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
