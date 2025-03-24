@extends('layout')

@section('title', 'View Delivery Record - NSBM')
@section('header', 'View Delivery Record')

@section('content')
<div class="mb-6">
    <a href="{{ route('delivery.index') }}" class="text-secondary hover:text-secondary-dark">
        <i class="fas fa-arrow-left mr-2"></i>Back to Delivery Records
    </a>
</div>

<div class="dashboard-card p-6 mb-6">
    <div class="border-b pb-4 mb-6" style="border-color: var(--color-border);">
        <div class="flex items-center">
            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $ward->name }}</h3>
                <p class="text-sm" style="color: var(--color-text-secondary);">Delivery record for {{ $delivery->report_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Report Details -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">Delivery Details</h3>

            <div class="flex space-x-2">
                <a href="{{ route('delivery.edit', $delivery->id) }}" class="btn btn-secondary">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>

                @can('delete', $delivery)
                <form action="{{ route('delivery.destroy', $delivery->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <div class="p-4 mb-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
            <div class="flex justify-between">
                <div>
                    <p class="mb-1" style="color: var(--color-text-secondary);">Report Date</p>
                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $delivery->report_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="mb-1" style="color: var(--color-text-secondary);">Total Deliveries</p>
                    <p class="font-medium text-lg" style="color: var(--color-primary);">{{ $delivery->total }}</p>
                </div>
                <div>
                    <p class="mb-1" style="color: var(--color-text-secondary);">Created By</p>
                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $delivery->user->name }}</p>
                </div>
                <div>
                    <p class="mb-1" style="color: var(--color-text-secondary);">Last Updated</p>
                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $delivery->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Types -->
    <div class="border-t pt-6 mb-6" style="border-color: var(--color-border);">
        <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Delivery Types</h3>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">SVD</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->svd }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">LSCS</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->lscs }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Vacuum</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->vacuum }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Forceps</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->forceps }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Breech</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->breech }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Eclampsia</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->eclampsia }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Twin</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->twin }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">MRP</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->mrp }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">FSB/MBS</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->fsb_mbs }}</p>
            </div>

            <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
                <p class="text-sm mb-1" style="color: var(--color-text-secondary);">BBA</p>
                <p class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $delivery->bba }}</p>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($delivery->notes)
    <div class="border-t pt-6" style="border-color: var(--color-border);">
        <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Notes</h3>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border);">
            <p style="color: var(--color-text-primary);">{{ $delivery->notes }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
