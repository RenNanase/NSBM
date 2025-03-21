@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Add New Infectious Disease Record</h3>
        <div>
            <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <form action="{{ route('infectious-diseases.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="disease" class="block text-sm font-medium text-gray-700">Disease Type</label>
                    <div class="mt-1">
                        <select id="disease" name="disease" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Disease</option>
                            @foreach(\App\Models\InfectiousDisease::diseaseTypes() as $disease)
                                <option value="{{ $disease }}" {{ old('disease') == $disease ? 'selected' : '' }}>{{ $disease }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('disease')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="patient_type" class="block text-sm font-medium text-gray-700">Patient Type</label>
                    <div class="mt-1">
                        <select id="patient_type" name="patient_type" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Patient Type</option>
                            @foreach(\App\Models\InfectiousDisease::patientTypes() as $type => $label)
                                <option value="{{ $type }}" {{ old('patient_type') == $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('patient_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="ward_id" class="block text-sm font-medium text-gray-700">Ward</label>
                    <div class="mt-1">
                        <select id="ward_id" name="ward_id" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Ward</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('ward_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="total" class="block text-sm font-medium text-gray-700">Total Cases</label>
                    <div class="mt-1">
                        <input type="number" name="total" id="total" min="0" value="{{ old('total', 0) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    @error('total')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <div class="mt-1">
                        <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                    </div>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('infectious-diseases.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
