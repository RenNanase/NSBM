@extends('layout')

@section('title', 'Emergency Room BOR Details - NSBM')
@section('header', 'Emergency Room BOR Details')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-secondary hover:text-secondary-dark">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR List
        </a>
    </div>

    <div class="p-4 rounded-lg mb-6" style="background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3);">
        <h2 class="text-2xl font-semibold" style="color: var(--color-secondary);">
            BOR Entry for {{ \Carbon\Carbon::parse($borEntry->date)->format('d M Y') }} - {{ $borEntry->shift }} Shift
        </h2>
        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
            Recorded by {{ $borEntry->user->username }} on {{ $borEntry->created_at->format('d M Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
        <div>
            <h3 class="text-lg font-medium mb-4 border-b pb-2" style="color: var(--color-text-primary); border-color: var(--color-border);">Patient Categories</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                        <span style="color: var(--color-text-primary);">Green (Non-Urgent)</span>
                    </span>
                    <span class="font-medium" style="color: #10b981;">{{ $borEntry->green }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                        <span style="color: var(--color-text-primary);">Yellow (Urgent)</span>
                    </span>
                    <span class="font-medium" style="color: #f59e0b;">{{ $borEntry->yellow }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                        <span style="color: var(--color-text-primary);">Red (Emergency)</span>
                    </span>
                    <span class="font-medium" style="color: #ef4444;">{{ $borEntry->red }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t" style="border-color: var(--color-border);">
                    <span class="font-semibold" style="color: var(--color-text-primary);">Shift Total</span>
                    <span class="font-bold" style="color: var(--color-primary);">{{ $borEntry->grand_total }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t" style="border-color: var(--color-border);">
                    <span class="font-semibold" style="color: var(--color-text-primary);">24 Hours Census</span>
                    <span class="font-bold" style="color: var(--color-secondary);">{{ $borEntry->hours_24_census }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium mb-4 border-b pb-2" style="color: var(--color-text-primary); border-color: var(--color-border);">Patient Outcomes</h3>
            <div class="grid grid-cols-1 gap-4">
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-primary);">Ambulance Calls</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $borEntry->ambulance_call }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-primary);">Admissions</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $borEntry->admission }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-primary);">Transfers</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $borEntry->transfer }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--color-text-primary);">Deaths</span>
                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $borEntry->death }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($borEntry->remarks))
    <div class="mb-6">
        <h3 class="text-lg font-medium mb-3 border-b pb-2" style="color: var(--color-text-primary); border-color: var(--color-border);">Remarks</h3>
        <div class="p-4 rounded-md" style="background-color: var(--color-bg-alt);">
            <p style="color: var(--color-text-primary);">{{ $borEntry->remarks }}</p>
        </div>
    </div>
    @endif

    <div class="flex justify-between mt-8">
        <a href="{{ route('emergency.bor.index') }}" class="btn inline-flex items-center px-6 py-2 rounded-md" style="background-color: var(--color-bg-alt); color: var(--color-text-primary);">
            Back to List
        </a>
        <div class="flex space-x-4">
            <a href="{{ route('emergency.bor.edit', $borEntry->id) }}" class="btn btn-primary inline-flex items-center px-6 py-2 rounded-md text-white">
                Edit Entry
            </a>
            <form action="{{ route('emergency.bor.destroy', $borEntry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-accent inline-flex items-center px-6 py-2 rounded-md text-white">
                    Delete Entry
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
