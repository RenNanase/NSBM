@extends('layout')

@section('title', 'Add Emergency Room BOR Entry - NSBM')
@section('header', 'Add Emergency Room BOR Entry')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-secondary hover:text-secondary-dark">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR List
        </a>
    </div>

    <form action="{{ route('emergency.bor.store') }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="p-4 mb-6 rounded-md" style="background-color: var(--color-accent-light); color: var(--color-accent); border-left: 4px solid var(--color-accent);" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($availableShifts && count($availableShifts) > 0)
            <div class="mb-8 p-4 rounded-lg" style="background-color: var(--color-primary-light); border: 1px solid var(--color-border);">
                <h3 class="text-lg font-medium mb-2" style="color: var(--color-secondary);">New Entry for {{ $today->format('d M Y') }}</h3>
                <p class="text-sm" style="color: var(--color-text-primary);">
                    You're creating a new entry for today. Please select a shift and fill in the patient counts.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="date" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Date</label>
                    <input type="date" name="date" id="date" value="{{ $today->format('Y-m-d') }}"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                    @error('date')
                        <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="shift" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Shift</label>
                    <select name="shift" id="shift"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        <option value="">Select Shift</option>
                        @foreach($availableShifts as $shift)
                            <option value="{{ $shift }}">{{ $shift }}</option>
                        @endforeach
                    </select>
                    @error('shift')
                        <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium mb-3" style="color: var(--color-text-primary);">Patient Categories</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4" style="border-color: #10b981; background-color: rgba(16, 185, 129, 0.1);">
                        <label for="green" class="block text-sm font-medium mb-1" style="color: #047857;">Green (Non-Urgent)</label>
                        <input type="number" name="green" id="green" value="{{ old('green', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('green')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border rounded-lg p-4" style="border-color: #f59e0b; background-color: rgba(245, 158, 11, 0.1);">
                        <label for="yellow" class="block text-sm font-medium mb-1" style="color: #b45309;">Yellow (Urgent)</label>
                        <input type="number" name="yellow" id="yellow" value="{{ old('yellow', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('yellow')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border rounded-lg p-4" style="border-color: #ef4444; background-color: rgba(239, 68, 68, 0.1);">
                        <label for="red" class="block text-sm font-medium mb-1" style="color: #b91c1c;">Red (Emergency)</label>
                        <input type="number" name="red" id="red" value="{{ old('red', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('red')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium mb-3" style="color: var(--color-text-primary);">Patient Outcomes</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="ambulance_call" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Ambulance Calls</label>
                        <input type="number" name="ambulance_call" id="ambulance_call" value="{{ old('ambulance_call', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('ambulance_call')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admission" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Admissions</label>
                        <input type="number" name="admission" id="admission" value="{{ old('admission', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('admission')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="transfer" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Transfers</label>
                        <input type="number" name="transfer" id="transfer" value="{{ old('transfer', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('transfer')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="death" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Deaths</label>
                        <input type="number" name="death" id="death" value="{{ old('death', 0) }}" min="0"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                        @error('death')
                            <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="remarks" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Remarks</label>
                <textarea name="remarks" id="remarks" rows="3"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none"
                    style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                    placeholder="Any additional notes or comments...">{{ old('remarks') }}</textarea>
                @error('remarks')
                    <p class="text-xs mt-1" style="color: var(--color-accent);">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary px-6 py-2 rounded-md">
                    Save Entry
                </button>
            </div>
        @else
            <div class="p-4 mb-6 rounded-md" style="background-color: rgba(245, 158, 11, 0.2); border-left: 4px solid #f59e0b; color: #b45309;" role="alert">
                <h3 class="font-medium">All shifts for today have been recorded</h3>
                <p class="mt-1">You have already recorded BOR data for all shifts today. To make changes, please edit the existing entries.</p>
                <div class="mt-3">
                    <a href="{{ route('emergency.bor.index') }}" class="btn btn-primary px-4 py-2 rounded-md inline-block" style="background-color: #f59e0b;">
                        View Today's Entries
                    </a>
                </div>
            </div>
        @endif
    </form>
</div>
@endsection
