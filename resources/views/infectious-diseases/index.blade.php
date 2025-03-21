@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Infectious Diseases</h3>
        <div>
            <a href="{{ route('infectious-diseases.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Add New Disease Entry
            </a>
            <a href="{{ route('infectious-diseases.report') }}" class="ml-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Generate Report
            </a>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('infectious-diseases.index') }}" method="GET" class="mb-6 flex gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            </div>
            <div class="w-48">
                <label for="disease_filter" class="block text-sm font-medium text-gray-700">Disease</label>
                <select id="disease_filter" name="disease" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Diseases</option>
                    @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                        <option value="{{ $disease }}" {{ request('disease') == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-48">
                <label for="ward_filter" class="block text-sm font-medium text-gray-700">Ward</label>
                <select id="ward_filter" name="ward_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Wards</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-48">
                <label for="patient_type_filter" class="block text-sm font-medium text-gray-700">Patient Type</label>
                <select id="patient_type_filter" name="patient_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="">All Types</option>
                    @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                        <option value="{{ $type }}" {{ request('patient_type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="self-end">
                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
                <a href="{{ route('infectious-diseases.index') }}" class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset
                </a>
            </div>
        </form>

        @if($infectiousDiseases->isEmpty())
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <p class="text-gray-500">No infectious disease records found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disease</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Type</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($infectiousDiseases as $disease)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">{{ $disease->disease }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $disease->ward->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ \App\Models\InfectiousDisease::patientTypes()[$disease->patient_type] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $disease->total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $disease->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $disease->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('infectious-diseases.show', $disease) }}" class="text-center text-blue-600  hover:text-blue-900">View</a>
                                    <a href="{{ route('infectious-diseases.edit', $disease) }}" class="text-center text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('infectious-diseases.destroy', $disease) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-center text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $infectiousDiseases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
