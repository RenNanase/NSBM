@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h3 class="text-xl font-semibold" style="color: var(--color-text-primary);">Infectious Diseases Records</h3>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('infectious-diseases.create') }}" class="btn btn-primary inline-flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white">
                <i class="fas fa-plus-circle mr-2"></i> Add New Record
            </a>
            <a href="{{ route('infectious-diseases.report') }}" class="btn btn-secondary inline-flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white">
                <i class="fas fa-chart-bar mr-2"></i> Reports
            </a>
        </div>
    </div>

    <div class="dashboard-card p-6 mb-6">
        <h4 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Search & Filter</h4>

        <form action="{{ route('infectious-diseases.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="disease_filter" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Disease Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-virus" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="disease_filter" name="disease" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">All Diseases</option>
                            @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                                <option value="{{ $disease }}" {{ request('disease') == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="ward_filter" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Ward</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hospital-alt" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="ward_filter" name="ward_id" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">All Wards</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="patient_type_filter" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Patient Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user-injured" style="color: var(--color-text-light);"></i>
                        </div>
                        <select id="patient_type_filter" name="patient_type" class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                            <option value="">All Types</option>
                            @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                                <option value="{{ $type }}" {{ request('patient_type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search" style="color: var(--color-text-light);"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by keywords..." class="pl-10 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border rounded-md text-sm font-medium mr-3" style="border-color: var(--color-border); color: var(--color-text-primary); background-color: var(--color-bg-alt);">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
                <button type="submit" class="btn btn-primary inline-flex items-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if($infectiousDiseases->isEmpty())
        <div class="p-4 mb-6 rounded-md" style="background-color: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b; color: #b45309;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">
                        No infectious disease records found with the current filters.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="dashboard-card shadow overflow-hidden rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                    <thead style="background-color: var(--color-secondary-dark); color: var(--color-text-inverse);">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Disease</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ward</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Patient Type</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created By</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--color-border);">
                        @foreach($infectiousDiseases as $disease)
                        <tr class="hover:bg-opacity-20 transition duration-150" style="background-color: var(--color-table-stripe); --tw-bg-opacity: 0.5;">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">{{ $disease->disease }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $disease->ward->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ \App\Models\InfectiousDisease::patientTypes()[$disease->patient_type] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium" style="background-color: rgba(59, 130, 246, 0.1); color: var(--color-text-primary);">{{ $disease->total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $disease->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm" style="color: var(--color-text-secondary);">{{ $disease->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('infectious-diseases.show', $disease) }}" style="color: var(--color-secondary);" class="hover:text-opacity-80" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('infectious-diseases.edit', $disease) }}" style="color: var(--color-primary);" class="hover:text-opacity-80" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('infectious-diseases.destroy', $disease) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color: var(--color-accent);" class="hover:text-opacity-80" title="Delete">
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

            <div class="px-4 py-3 border-t sm:px-6" style="border-color: var(--color-border); background-color: var(--color-bg-alt);">
                {{ $infectiousDiseases->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
