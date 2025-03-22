@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Infectious Diseases Records</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('infectious-diseases.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus-circle mr-2"></i> Add New Record
            </a>
            <a href="{{ route('infectious-diseases.report') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-chart-bar mr-2"></i> Reports
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h4 class="text-lg font-medium text-gray-800 mb-4">Search & Filter</h4>

        <form action="{{ route('infectious-diseases.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="disease_filter" class="block text-sm font-medium text-gray-700 mb-1">Disease Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-virus text-gray-400"></i>
                        </div>
                        <select id="disease_filter" name="disease" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Diseases</option>
                            @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                                <option value="{{ $disease }}" {{ request('disease') == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="ward_filter" class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hospital-alt text-gray-400"></i>
                        </div>
                        <select id="ward_filter" name="ward_id" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Wards</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="patient_type_filter" class="block text-sm font-medium text-gray-700 mb-1">Patient Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user-injured text-gray-400"></i>
                        </div>
                        <select id="patient_type_filter" name="patient_type" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Types</option>
                            @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                                <option value="{{ $type }}" {{ request('patient_type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by keywords..." class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if($infectiousDiseases->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No infectious disease records found with the current filters.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disease</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Type</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($infectiousDiseases as $disease)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $disease->disease }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $disease->ward->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \App\Models\InfectiousDisease::patientTypes()[$disease->patient_type] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-blue-50">{{ $disease->total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $disease->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $disease->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('infectious-diseases.show', $disease) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('infectious-diseases.edit', $disease) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('infectious-diseases.destroy', $disease) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $infectiousDiseases->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
