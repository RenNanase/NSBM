@extends('layout')

@section('title', 'Ward Records - NSBM')
@section('header', 'Ward Records')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="border-b pb-4 mb-6" style="border-color: var(--color-border);">
        <div class="flex items-center">
            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $ward->name }} - Ward Records</h3>
                <p class="text-sm" style="color: var(--color-text-secondary);">
                    Total Beds: {{ $ward->total_bed }} | Licensed Beds: {{ $ward->total_licensed_op_beds }}
                </p>
            </div>
        </div>
    </div>

    <!-- Date Selection Form -->
    <div class="mb-6">
        <form action="{{ route('ward.records') }}" method="GET" class="flex items-end space-x-4">
            <div class="flex-1">
                <label for="date" class="block mb-2 text-sm font-medium" style="color: var(--color-text-primary);">Select Date</label>
                <input type="date" name="date" id="date" value="{{ $recordDate->format('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-md focus:outline-none"
                       style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-2"></i> View Records
                </button>
            </div>
            <div>
                <a href="{{ route('ward.entry.create') }}" class="btn btn-secondary">
                    <i class="fas fa-plus-circle mr-2"></i> New Entry
                </a>
            </div>
        </form>
    </div>

    <!-- Date Navigation -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('ward.records', ['date' => $recordDate->copy()->subDay()->format('Y-m-d')]) }}"
           class="btn btn-secondary text-xs">
            <i class="fas fa-chevron-left mr-1"></i> Previous Day
        </a>
        <a href="{{ route('ward.records', ['date' => now()->format('Y-m-d')]) }}"
           class="btn btn-secondary text-xs {{ $recordDate->isToday() ? 'opacity-50 cursor-not-allowed' : '' }}"
           {{ $recordDate->isToday() ? 'disabled' : '' }}>
            Today
        </a>
        <a href="{{ route('ward.records', ['date' => $recordDate->copy()->addDay()->format('Y-m-d')]) }}"
           class="btn btn-secondary text-xs {{ $recordDate->isToday() ? 'opacity-50 cursor-not-allowed' : '' }}"
           {{ $recordDate->isToday() ? 'disabled' : '' }}>
            Next Day <i class="fas fa-chevron-right ml-1"></i>
        </a>
    </div>

    <!-- Record Date Display -->
    <div class="mb-6 p-4 rounded-lg" style="background-color: var(--color-primary-dark); border: 1px solid var(--color-border);">
        <h2 class="text-xl font-semibold" style="color: var(--color-secondary);">
            Records for {{ $recordDate->format('d M Y') }}
        </h2>
        <p class="text-sm mt-2" style="color: var(--color-text-secondary);">
            @if($entries->isEmpty())
                No entries recorded for this date.
            @else
                Showing {{ $entries->count() }} of {{ $shifts->count() }} shifts recorded.
            @endif
        </p>
    </div>

    @if($entries->isEmpty())
        <div class="flex flex-col items-center justify-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-text-light);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-lg mb-4" style="color: var(--color-text-primary);">No ward entries recorded for this date</p>
            <a href="{{ route('ward.entry.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i> Create New Entry
            </a>
        </div>
    @else
        <!-- Shift Entries -->
        <div class="space-y-6">
            @foreach($entries as $entry)
                <div class="p-4 rounded-lg" style="background-color: var(--color-primary-light); border: 1px solid var(--color-border);">
                    <div class="flex justify-between items-start mb-4 pb-3 border-b" style="border-color: var(--color-border);">
                        <div class="flex items-center">
                            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-secondary-light);">
                                <i class="fas fa-clock" style="color: var(--color-secondary);"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-lg" style="color: var(--color-text-primary);">{{ $entry->shift->name }}</h3>
                                <p class="text-sm" style="color: var(--color-text-secondary);">
                                    Recorded by {{ $entry->user->username }} at {{ $entry->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('ward.entry.edit', $entry) }}" class="btn btn-secondary text-xs">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Patient Information -->
                        <div class="p-3 rounded" style="background-color: var(--color-bg-alt);">
                            <h4 class="font-medium mb-2" style="color: var(--color-secondary);">Patient Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">CF Patients:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->cf_patient }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Total Patients:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_patient }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Movement -->
                        <div class="p-3 rounded" style="background-color: var(--color-bg-alt);">
                            <h4 class="font-medium mb-2" style="color: var(--color-secondary);">Patient Movement</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Admissions:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_admission }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Transfer In:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_transfer_in }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Transfer Out:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_transfer_out }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Discharges:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_discharge }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">AOR:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->aor }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Information -->
                        <div class="p-3 rounded" style="background-color: var(--color-bg-alt);">
                            <h4 class="font-medium mb-2" style="color: var(--color-secondary);">Staff Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Staff on Duty:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->total_staff_on_duty }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span style="color: var(--color-text-secondary);">Overtime Hours:</span>
                                    <span class="font-medium" style="color: var(--color-text-primary);">{{ $entry->overtime }}</span>
                                </div>

                                @if($entry->shift->name == 'ND SHIFT' && $entry->total_daily_patients)
                                <div class="mt-3 pt-2 border-t" style="border-color: var(--color-border);">
                                    <div class="flex justify-between">
                                        <span style="color: var(--color-text-secondary);">Daily Patients:</span>
                                        <span class="font-medium text-lg" style="color: var(--color-primary);">{{ $entry->total_daily_patients }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Missing Shifts -->
        @if($missingShifts->isNotEmpty())
            <div class="mt-8 p-4 rounded-lg" style="background-color: var(--color-secondary-light); border: 1px solid var(--color-border);">
                <h3 class="font-medium mb-3" style="color: var(--color-text-primary);">Missing Shifts</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($missingShifts as $shift)
                        <div class="p-3 rounded flex items-center" style="background-color: var(--color-bg-alt);">
                            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                                <i class="fas fa-clock" style="color: var(--color-text-light);"></i>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--color-text-primary);">{{ $shift->name }}</p>
                                <p class="text-sm" style="color: var(--color-text-secondary);">Not recorded</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('ward.entry.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Add Missing Shift
                    </a>
                </div>
            </div>
        @endif
    @endif

    <div class="mt-8 flex justify-between">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
        <div class="flex space-x-3">
            <a href="{{ route('ward.entry.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i> New Entry
            </a>
            @if($recordDate->isToday() && $missingShifts->isEmpty() && $entries->isNotEmpty())
                <a href="{{ route('census.create') }}" class="btn btn-secondary">
                    <i class="fas fa-chart-bar mr-2"></i> Record Census
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
