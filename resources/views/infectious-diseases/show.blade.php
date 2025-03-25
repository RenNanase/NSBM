@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between">
        <h3 class="text-lg font-medium leading-6" style="color: var(--color-text-primary);">Infectious Disease Details</h3>
        <div>
            <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-3 py-2 border rounded-md text-sm leading-4 font-medium" style="border-color: var(--color-border); color: var(--color-text-primary); background-color: var(--color-bg-alt);">
                Back to List
            </a>
            <a href="{{ route('infectious-diseases.edit', $infectiousDisease) }}" class="ml-3 inline-flex items-center px-3 py-2 rounded-md shadow-sm text-sm leading-4 font-medium text-white btn btn-primary">
                Edit
            </a>
        </div>
    </div>

    <div class="mt-6 dashboard-card shadow overflow-hidden rounded-lg">
        <div class="px-4 py-5 sm:px-6" style="background-color: var(--color-bg-alt);">
            <h3 class="text-lg leading-6 font-medium" style="color: var(--color-text-primary);">{{ $infectiousDisease->disease }}</h3>
            <p class="mt-1 max-w-2xl text-sm" style="color: var(--color-text-secondary);">Reported on {{ $infectiousDisease->created_at->format('F j, Y') }}</p>
        </div>
        <div class="border-t" style="border-color: var(--color-border);">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" style="background-color: var(--color-bg-alt); background-opacity: 0.3;">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Disease</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->disease }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Ward</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->ward->name }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" style="background-color: var(--color-bg-alt); background-opacity: 0.3;">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Patient Type</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ \App\Models\InfectiousDisease::patientTypes()[$infectiousDisease->patient_type] }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Total Cases</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->total }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" style="background-color: var(--color-bg-alt); background-opacity: 0.3;">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Created By</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->user->username }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Created At</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->created_at->format('F j, Y g:i A') }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" style="background-color: var(--color-bg-alt); background-opacity: 0.3;">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Last Updated</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->updated_at->format('F j, Y g:i A') }}</dd>
                </div>
                @if($infectiousDisease->notes)
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium" style="color: var(--color-text-secondary);">Notes</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2" style="color: var(--color-text-primary);">{{ $infectiousDisease->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <form action="{{ route('infectious-diseases.destroy', $infectiousDisease) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md shadow-sm text-sm leading-4 font-medium text-white btn btn-accent">
                Delete Entry
            </button>
        </form>
    </div>
</div>
@endsection
