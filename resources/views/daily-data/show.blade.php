@extends('layout')

@section('title', 'Daily Data Details - NSBM')
@section('header', 'Daily Data Details')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('daily-data.index') }}" class="text-secondary hover:text-secondary-dark">
            <i class="fas fa-arrow-left mr-2"></i>Back to Daily Data List
        </a>
    </div>

    <div class="bg-primary-light p-4 rounded-lg mb-6" style="border: 1px solid var(--color-border);">
        <h2 class="text-2xl font-semibold" style="color: var(--color-secondary);">
            Daily Data Entry for {{ $dailyData->date->format('d M Y') }} - {{ $ward->name }}
        </h2>
        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
            Recorded by {{ $dailyData->user->username }} on {{ $dailyData->created_at->format('d M Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="text-lg font-medium mb-4 pb-2 border-b" style="color: var(--color-secondary); border-color: var(--color-border);">Daily Health Statistics</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Deaths</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->death }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Neonatal Jaundice</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->neonatal_jaundice }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Bedridden Cases</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->bedridden_case }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Incident Reports</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->incident_report }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium mb-4 pb-2 border-b" style="color: var(--color-secondary); border-color: var(--color-border);">Submission Details</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Ward</span>
                    <span class="badge badge-secondary">{{ $ward->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Date</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->date->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Recorded By</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->user->username }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Created At</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-secondary);">Last Updated</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $dailyData->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($dailyData->remarks))
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-3 pb-2 border-b" style="color: var(--color-secondary); border-color: var(--color-border);">Remarks</h3>
        <div class="bg-primary-light p-4 rounded-md" style="border: 1px solid var(--color-border);">
            <p style="color: var(--color-text-primary);">{{ $dailyData->remarks }}</p>
        </div>
    </div>
    @endif

    <div class="flex justify-between mt-8">
        <a href="{{ route('daily-data.index') }}" class="btn btn-secondary">
            Back to List
        </a>
        <div class="flex space-x-4">
            <a href="{{ route('daily-data.edit', $dailyData->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-2"></i> Edit Entry
            </a>
            @if(Auth::user()->isAdmin())
            <form action="{{ route('daily-data.destroy', $dailyData->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Entry
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
