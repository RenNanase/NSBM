@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Edit Infectious Disease Record</h3>
        <div>
            <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <h4 class="text-lg font-medium text-blue-800">Record ID: #{{ $infectiousDisease->id }}</h4>
            <p class="text-sm text-blue-600 mt-1">
                Created on {{ $infectiousDisease->created_at->format('d M Y H:i') }} by {{ $infectiousDisease->user->username }}
            </p>
        </div>

        <form action="{{ route('infectious-diseases.update', $infectiousDisease) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="disease" class="block text-sm font-medium text-gray-700 mb-1">Disease Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-virus text-gray-400"></i>
                        </div>
                        <select id="disease" name="disease" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select Disease</option>
                            @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                                <option value="{{ $disease }}" {{ old('disease', $infectiousDisease->disease) == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('disease')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="patient_type" class="block text-sm font-medium text-gray-700 mb-1">Patient Type</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-user-injured text-gray-400"></i>
                        </div>
                        <select id="patient_type" name="patient_type" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select Patient Type</option>
                            @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                                <option value="{{ $type }}" {{ old('patient_type', $infectiousDisease->patient_type) == $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('patient_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ward_id" class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hospital-alt text-gray-400"></i>
                        </div>
                        <select id="ward_id" name="ward_id" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ old('ward_id', $infectiousDisease->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('ward_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium text-gray-700 mb-1">Total Cases</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-hashtag text-gray-400"></i>
                        </div>
                        <input type="number" name="total" id="total" min="0" value="{{ old('total', $infectiousDisease->total) }}" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <div class="relative">
                    <textarea id="notes" name="notes" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Any additional information about this case...">{{ old('notes', $infectiousDisease->notes) }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
