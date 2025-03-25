@extends('layout')

@section('title', 'Edit Daily Data Entry - NSBM')
@section('header', 'Edit Daily Data Entry')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('daily-data.index') }}" class="text-secondary hover:text-secondary-dark">
            <i class="fas fa-arrow-left mr-2"></i>Back to Daily Data List
        </a>
    </div>

    <div class="bg-primary-light p-4 rounded-lg mb-6" style="border: 1px solid var(--color-border);">
        <h2 class="text-xl font-semibold" style="color: var(--color-secondary);">
            Editing Daily Data Entry for {{ $dailyData->date->format('d M Y') }} - {{ $ward->name }}
        </h2>
        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
            Originally recorded by {{ $dailyData->user->username }} on {{ $dailyData->created_at->format('d M Y H:i') }}
        </p>
    </div>

    <form action="{{ route('daily-data.update', $dailyData->id) }}" method="POST" class="daily-data-form">
        @csrf
        @method('PUT')

        <div class="bg-primary-light p-4 rounded-md mb-6" style="border: 1px solid var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Daily Health Statistics</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="death" class="block mb-2" style="color: var(--color-text-primary);">Deaths</label>
                    <input type="number" id="death" name="death" min="0" value="{{ old('death', $dailyData->death) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('death') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('death')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="neonatal_jaundice" class="block mb-2" style="color: var(--color-text-primary);">Neonatal Jaundice</label>
                    <input type="number" id="neonatal_jaundice" name="neonatal_jaundice" min="0" value="{{ old('neonatal_jaundice', $dailyData->neonatal_jaundice) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('neonatal_jaundice') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('neonatal_jaundice')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bedridden_case" class="block mb-2" style="color: var(--color-text-primary);">Bedridden Cases</label>
                    <input type="number" id="bedridden_case" name="bedridden_case" min="0" value="{{ old('bedridden_case', $dailyData->bedridden_case) }}"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none text-center @error('bedridden_case') border-accent @enderror"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    @error('bedridden_case')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="incident_report" class="block mb-2" style="color: var(--color-text-primary);">Incident Reports</label>
                    <input type="number" id="incident_report" name="incident_report" min="0" value="{{ old('incident_report', $dailyData->incident_report) }}"
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
                      style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">{{ old('remarks', $dailyData->remarks) }}</textarea>
            @error('remarks')
                <p class="text-accent text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('daily-data.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> Update Entry
            </button>
        </div>
    </form>
</div>
@endsection
