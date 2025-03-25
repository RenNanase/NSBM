@extends('layout')

@section('title', 'Dashboard - NSBM')
@section('header', 'Dashboard')

@section('content')
<div class="dashboard-card p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold" style="color: var(--color-secondary);">Recent Entries</h3>
        <div class="text-sm" style="color: var(--color-text-light);">
            <i class="fas fa-clock mr-1"></i> Last updated: {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

    {{-- recent entry --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border rounded-lg overflow-hidden dashboard-table"
            style="border-color: var(--color-border);">
            <thead>
                <tr>
                    <th class="py-3 px-4 text-left font-semibold">Ward</th>
                    <th class="py-3 px-4 text-center font-semibold">C/F Patient</th>
                    <th class="py-3 px-4 text-center font-semibold">AM Shift</th>
                    <th class="py-3 px-4 text-center font-semibold">PM Shift</th>
                    <th class="py-3 px-4 text-center font-semibold">Night Duty</th>
                    <th class="py-3 px-4 text-center font-semibold">Total Daily Patient</th>
                    <th class="py-3 px-4 text-left font-semibold">Staff Name</th>
                    <th class="py-3 px-4 text-center font-semibold">Date</th>
                    @if(Auth::user()->isAdmin())
                    <th class="py-3 px-4 text-center font-semibold">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--color-border);">
                @if($recentEntries->isEmpty())
                <tr>
                    <td colspan="{{ Auth::user()->isAdmin() ? 9 : 8 }}" class="py-6 px-4 text-center"
                        style="color: var(--color-text-light);">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-clipboard-list text-3xl mb-2" style="color: var(--color-text-light);"></i>
                            <p>No entries recorded yet</p>
                            <p class="text-sm mt-1" style="color: var(--color-text-light);">Entries will appear here
                                once created</p>
                        </div>
                    </td>
                </tr>
                @else
                @foreach($recentEntries as $entry)
                <tr class="hover:bg-opacity-20 transition duration-150"
                    style="background-color: var(--color-table-stripe); --tw-bg-opacity: 0.5;">
                    <td class="py-4 px-4 font-medium" style="color: var(--color-text-primary);">{{ $ward->name }}</td>
                    <td class="py-4 px-4 text-center">{{ $entry->cf_patient }}</td>
                    <td class="py-4 px-4 text-center">
                        @if($entry->shift->name == 'AM SHIFT')
                        <span class="font-medium" style="color: var(--color-secondary);">{{ $entry->total_patient
                            }}</span>
                        <div class="mt-1 text-xs" style="color: var(--color-text-secondary);">
                            <div>Total Bed BOR: <span class="font-medium">{{ number_format($entry->total_bed_bor, 2)
                                    }}%</span></div>
                            <div>Licensed Bed BOR: <span class="font-medium">{{ number_format($entry->licensed_bed_bor,
                                    2) }}%</span></div>
                        </div>
                        @else
                        <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-center">
                        @if($entry->shift->name == 'PM SHIFT')
                        <span class="font-medium" style="color: var(--color-secondary);">{{ $entry->total_patient
                            }}</span>
                        <div class="mt-1 text-xs" style="color: var(--color-text-secondary);">
                            <div>Total Bed BOR: <span class="font-medium">{{ number_format($entry->total_bed_bor, 2)
                                    }}%</span></div>
                            <div>Licensed Bed BOR: <span class="font-medium">{{ number_format($entry->licensed_bed_bor,
                                    2) }}%</span></div>
                        </div>
                        @else
                        <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-center">
                        @if($entry->shift->name == 'ND SHIFT')
                        <span class="font-medium" style="color: var(--color-secondary);">{{ $entry->total_patient
                            }}</span>
                        <div class="mt-1 text-xs" style="color: var(--color-text-secondary);">
                            <div>Total Bed BOR: <span class="font-medium">{{ number_format($entry->total_bed_bor, 2)
                                    }}%</span></div>
                            <div>Licensed Bed BOR: <span class="font-medium">{{ number_format($entry->licensed_bed_bor,
                                    2) }}%</span></div>
                        </div>
                        @else
                        <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-center">
                        @if($entry->shift->name == 'ND SHIFT' && $entry->total_daily_patients)
                        <span class="badge badge-primary">{{ $entry->total_daily_patients }}</span>
                        @else
                        <span style="color: var(--color-text-light);">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-4">{{ $entry->user->username }}</td>
                    <td class="py-4 px-4 text-center text-sm">
                        <div class="font-medium">{{ $entry->created_at->format('M d, Y') }}</div>
                        <div style="color: var(--color-text-light);">{{ $entry->created_at->format('H:i') }}</div>
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td class="py-4 px-4 text-center">
                        <a href="{{ route('ward.entry.edit', $entry) }}" class="btn btn-primary text-xs">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="dashboard-card p-6">
        <h3 class="text-lg font-bold mb-5 pb-2 border-b"
            style="color: var(--color-secondary); border-color: var(--color-border);">Ward Information</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Ward Name</span>
                <span class="badge badge-secondary">{{ $ward->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Total Beds</span>
                <span class="font-semibold" style="color: var(--color-text-primary);">{{ $ward->total_bed }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Licensed Op. Beds</span>
                <span class="font-semibold" style="color: var(--color-text-primary);">{{ $ward->total_licensed_op_beds
                    }}</span>
            </div>
            @if(session('next_shift_cf_patient') && session('next_shift_cf_patient_ward_id') == $ward->id &&
            session('next_shift_cf_patient_date') == $today)
            <div class="p-4 rounded-lg mt-6" style="background-color: var(--color-primary-light);">
                <div class="flex justify-between items-center">
                    <span class="font-medium" style="color: var(--color-secondary-dark);">Next Shift CF Patient</span>
                    <span class="badge badge-accent">
                        {{ session('next_shift_cf_patient') }}
                    </span>
                </div>
                <p class="text-sm mt-2" style="color: var(--color-secondary);">
                    <i class="fas fa-info-circle mr-1"></i> This value will be auto-filled in the next shift form.
                </p>
            </div>
            @endif
        </div>
    </div>

    <div class="dashboard-card p-6">
        <h3 class="text-lg font-bold mb-5 pb-2 border-b"
            style="color: var(--color-secondary); border-color: var(--color-border);">Today's Shift Status</h3>
        <div class="space-y-4">
            @foreach($shifts as $shift)
            <div class="flex justify-between items-center">
                <span class="font-medium" style="color: var(--color-text-secondary);">{{ $shift->name }}</span>
                @if(in_array($shift->id, $filledShifts))
                <span class="badge badge-primary inline-flex items-center">
                    <i class="fas fa-check-circle mr-1.5"></i>
                    Filled
                </span>
                @else
                <a href="{{ route('ward.entry.create') }}" class="btn btn-primary text-xs">
                    <i class="fas fa-plus-circle mr-1.5"></i>
                    Fill Now
                </a>
                @endif
            </div>
            @endforeach

            <div class="mt-8 pt-4 border-t" style="border-color: var(--color-border);">
                <div class="flex items-center justify-center">
                    <span class="badge badge-secondary inline-flex items-center">
                        <i class="fas fa-info-circle mr-1.5"></i>
                        {{ count($filledShifts) }}/{{ count($shifts) }} shifts completed today
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- census summary --}}
    <div class="dashboard-card p-6">
        <div class="flex justify-between items-center mb-5 pb-2 border-b" style="border-color: var(--color-border);">
            <h3 class="text-lg font-bold" style="color: var(--color-secondary);">Census Summary</h3>
            <a href="{{ route('census.create') }}" class="btn btn-primary text-xs">
                @if(isset($censusEntry) && $censusEntry)
                    @if($censusEntry->created_at->format('Y-m-d') === $today)
                        <i class="fas fa-edit mr-1.5"></i> Update Today's Census
                    @else
                        <i class="fas fa-plus-circle mr-1.5"></i> Add Today's Census
                    @endif
                @else
                    <i class="fas fa-plus-circle mr-1.5"></i> Add Census
                @endif
            </a>
        </div>
        @if(isset($censusEntry) && $censusEntry)
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">24H Census</span>
                <span class="font-semibold" style="color: var(--color-text-primary);">{{ $censusEntry->hours24_census }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">CF Patients at 24:00</span>
                <span class="font-semibold" style="color: var(--color-text-primary);">{{ $censusEntry->cf_patient_2400 }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Bed Occupancy Rate</span>
                <span class="badge badge-secondary">{{ number_format($censusEntry->bed_occupancy_rate, 2) }}%</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Recorded Date</span>
                <span class="text-sm" style="color: var(--color-text-light);">{{ $censusEntry->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span style="color: var(--color-text-secondary);">Last Updated</span>
                <span class="text-sm" style="color: var(--color-text-light);">{{ $censusEntry->updated_at->format('M d, Y H:i') }}</span>
            </div>
            @if($censusEntry->created_at->format('Y-m-d') !== $today)
            <div class="mt-3 p-2 rounded-md" style="background-color: var(--color-secondary-light); border: 1px solid var(--color-border);">
                <p class="text-xs text-center" style="color: var(--color-primary-dark);">
                    <i class="fas fa-info-circle mr-1"></i> This is the most recent census data available. No data has been recorded for today.
                </p>
            </div>
            @endif
            @if(Auth::user()->isAdmin())
            <div class="mt-6 pt-4 border-t flex justify-end" style="border-color: var(--color-border);">
                <a href="{{ route('census.edit', $censusEntry->id) }}" class="btn btn-secondary text-xs">
                    <i class="fas fa-edit mr-1.5"></i>
                    Edit Census Data
                </a>
            </div>
            @endif
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-10 text-center">
            <i class="fas fa-chart-bar text-3xl mb-3" style="color: var(--color-text-light);"></i>
            <p style="color: var(--color-text-secondary);">No census data available</p>
            <a href="{{ route('census.create') }}" class="btn btn-primary mt-4 text-sm">
                <i class="fas fa-plus-circle mr-1.5"></i>
                Add Census Data
            </a>
        </div>
        @endif
    </div>
</div>
@endsection