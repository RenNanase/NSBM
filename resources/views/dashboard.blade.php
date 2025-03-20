@extends('layout')

@section('title', 'Dashboard - NSBM')
@section('header', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold font-['Noto_Sans_JP'] uppercase">Recent Entries</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-black text-white text-sm uppercase">
                    <th class="py-3 px-4 text-center font-['Noto_Sans_JP']">Ward</th>
                    <th class="py-3 px-4 text-center">C/F Patient</th>
                    <th class="py-3 px-4 text-center">AM Shift</th>
                    <th class="py-3 px-4 text-center">PM Shift</th>
                    <th class="py-3 px-4 text-center">Night Duty</th>
                    <th class="py-3 px-4 text-center">Total Daily Patient</th>
                    <th class="py-3 px-4 text-center">Staff Name</th>
                    <th class="py-3 px-4 text-center">Date</th>
                    @if(Auth::user()->isAdmin())
                    <th class="py-3 px-4 text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($recentEntries->isEmpty())
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? 9 : 8 }}" class="py-4 px-4 text-center text-gray-500">No entries recorded yet</td>
                    </tr>
                @else
                    @foreach($recentEntries as $entry)
                        <tr class="hover:bg-pink-50">
                            <td class="py-3 px-4 text-center font-['Noto_Sans_JP'] uppercase">{{ $ward->name }}</td>

                            <td class="py-3 px-4 text-center">{{ $entry->cf_patient }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($entry->shift->name == 'AM SHIFT')
                                    <span class="font-medium">{{ $entry->total_patient }}</span><br>
                                    <span class="text-xs text-gray-500">Total Bed BOR: {{ number_format($entry->total_bed_bor, 2) }}%</span><br>
                                    <span class="text-xs text-gray-500">Licensed Bed BOR: {{ number_format($entry->licensed_bed_bor, 2) }}%</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($entry->shift->name == 'PM SHIFT')
                                    <span class="font-medium">{{ $entry->total_patient }}</span><br>
                                    <span class="text-xs text-gray-500">Total Bed BOR: {{ number_format($entry->total_bed_bor, 2) }}%</span><br>
                                    <span class="text-xs text-gray-500">Licensed Bed BOR: {{ number_format($entry->licensed_bed_bor, 2) }}%</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($entry->shift->name == 'ND SHIFT')
                                    <span class="font-medium">{{ $entry->total_patient }}</span><br>
                                    <span class="text-xs text-gray-500">Total Bed BOR: {{ number_format($entry->total_bed_bor, 2) }}%</span><br>
                                    <span class="text-xs text-gray-500">Licensed Bed BOR: {{ number_format($entry->licensed_bed_bor, 2) }}%</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($entry->shift->name == 'ND SHIFT' && $entry->total_daily_patients)
                                    <span class="font-medium">{{ $entry->total_daily_patients }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">{{ $entry->user->username }}</td>
                            <td class="py-3 px-4 text-center">{{ $entry->created_at->format('M d, Y') }}<br>{{ $entry->created_at->format('H:i') }}</td>
                            @if(Auth::user()->isAdmin())
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('ward.entry.edit', $entry) }}" class="bg-amber-100 text-amber-800 hover:bg-amber-200 px-2 py-1 rounded text-xs font-medium inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
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

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Ward Information</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Ward Name:</span>
                <span class="font-medium font-['Noto_Sans_JP'] uppercase">{{ $ward->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Beds:</span>
                <span class="font-medium text-center">{{ $ward->total_bed }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Licensed Op. Beds:</span>
                <span class="font-medium">{{ $ward->total_licensed_op_beds }}</span>
            </div>
            @if(session('next_shift_cf_patient') && session('next_shift_cf_patient_ward_id') == $ward->id && session('next_shift_cf_patient_date') == $today)
                <div class="flex justify-between mt-4 pt-3 border-t border-gray-200">
                    <span class="text-gray-600 font-medium">Next Shift CF Patient:</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-lg font-semibold">
                        {{ session('next_shift_cf_patient') }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    This value will be auto-filled in the next shift form.
                </p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Today's Shift Status</h3>
        <div class="space-y-3">
            @foreach($shifts as $shift)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-['Noto_Sans_JP'] uppercase">{{ $shift->name }}:</span>
                    @if(in_array($shift->id, $filledShifts))
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Filled
                        </span>
                    @else
                        <a href="{{ route('ward.entry.create') }}" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full hover:bg-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Fill Now
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold font-['Noto_Sans_JP'] uppercase">Census Summary</h3>
            <a href="{{ route('census.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md text-sm">
                {{ isset($censusEntry) && $censusEntry ? 'Update Census' : 'Add Census' }}
            </a>
        </div>
        @if(isset($censusEntry) && $censusEntry)
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">24H Census:</span>
                    <span class="font-medium">{{ $censusEntry->hours24_census }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">CF Patients at 24:00:</span>
                    <span class="font-medium">{{ $censusEntry->cf_patient_2400 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bed Occupancy Rate:</span>
                    <span class="font-medium">{{ number_format($censusEntry->bed_occupancy_rate, 2) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Last Updated:</span>
                    <span class="font-medium">{{ $censusEntry->updated_at->format('M d, Y H:i') }}</span>
                </div>
                @if(Auth::user()->isAdmin())
                <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('census.edit', $censusEntry->id) }}" class="bg-amber-100 text-amber-800 hover:bg-amber-200 px-3 py-1 rounded text-xs font-medium inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Census Data
                    </a>
                </div>
                @endif
            </div>
        @else
            <div class="text-gray-500 text-center py-4">
                No census data available
            </div>
        @endif
    </div>
</div>


@endsection
