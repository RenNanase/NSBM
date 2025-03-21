@extends('layout')

@section('title', 'Emergency Department Dashboard - NSBM')
@section('header', 'Emergency Department Dashboard')

@section('content')


<!-- Emergency Department Main Features -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Emergency Room BOR Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Emergency Room BOR</h3>

        <div class="flex justify-center mb-4">
            <div class="bg-gray-100 rounded-lg p-6 w-full">
                <div class="flex flex-col items-center">
                    <div class="text-5xl font-bold text-pink-600 mb-2">
                        @if(isset($currentBOR))
                            {{ number_format($currentBOR, 2) }}%
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="text-sm text-gray-600">Current Bed Occupancy Rate</div>
                </div>
            </div>
        </div>

        <div class="space-y-4 mt-6">
            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                <span class="text-gray-600">Ward Name:</span>
                <span class="font-medium font-['Noto_Sans_JP'] uppercase">{{ $ward->name }}</span>
            </div>

            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                <span class="text-gray-600">Last Updated:</span>
                <span class="font-medium">
                    @if(isset($recentEntries) && !$recentEntries->isEmpty())
                        {{ $recentEntries->first()->created_at->format('M d, Y H:i') }}
                    @else
                        N/A
                    @endif
                </span>
            </div>

            @if(session('next_shift_cf_patient') && session('next_shift_cf_patient_ward_id') == $ward->id && session('next_shift_cf_patient_date') == $today)
            <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                <span class="text-gray-600">Next Shift C/F Patient:</span>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-lg font-semibold">
                    {{ session('next_shift_cf_patient') }}
                </span>
            </div>
            @endif

            <div class="pt-2">
                <a href="#" class="block w-full bg-pink-600 hover:bg-pink-700 text-white text-center py-3 px-4 rounded-md transition duration-300">
                    <i class="fas fa-history mr-2"></i> View BOR History
                </a>
            </div>
        </div>
    </div>

    <!-- Infectious Disease Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Infectious Disease</h3>

        <div class="p-4 bg-pink-50 rounded-lg border border-pink-100 mb-4">
            <h4 class="font-medium text-pink-800 mb-3 text-center">Recent Entries</h4>
            <div class="grid grid-cols-2 gap-3">
                {{-- only show date and time for the latest update --}}
            </div>
        </div>
    </div>
</div>

<!-- Additional Emergency Department Information -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">ER Patient Status</h3>
        <div class="flex flex-col space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Critical Patients:</span>
                <span class="bg-red-100 text-red-800 px-2 py-1 rounded font-medium">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Semi-Critical:</span>
                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded font-medium">0</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Stable Patients:</span>
                <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-medium">0</span>
            </div>
            <div class="mt-2 pt-4 border-t border-gray-200">
                <a href="#" class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white text-center py-2 px-4 rounded-md">
                    Update Patient Status
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Available Resources</h3>
        <div class="flex flex-col space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Available Beds:</span>
                <span class="font-medium">5</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">ICU Beds:</span>
                <span class="font-medium">2</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Available Staff:</span>
                <span class="font-medium">8</span>
            </div>
            <div class="mt-2 pt-4 border-t border-gray-200">
                <a href="#" class="block w-full bg-indigo-500 hover:bg-indigo-600 text-white text-center py-2 px-4 rounded-md">
                    Update Resources
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 font-['Noto_Sans_JP'] uppercase text-center">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-3">
            <a href="#" class="block bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-md transition">
                <i class="fas fa-ambulance mr-2"></i> Register New ER Patient
            </a>
            <a href="#" class="block bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-md transition">
                <i class="fas fa-procedures mr-2"></i> Patient Discharge
            </a>
            <a href="#" class="block bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-md transition">
                <i class="fas fa-file-medical mr-2"></i> Generate ER Report
            </a>
            <a href="#" class="block bg-yellow-500 hover:bg-yellow-600 text-white text-center py-3 px-4 rounded-md transition">
                <i class="fas fa-exclamation-triangle mr-2"></i> Emergency Protocols
            </a>
        </div>
    </div>
</div>

@endsection
