@extends('layout')

@section('title', 'Ward Access Denied - NSBM')
@section('header', 'Access Denied')

@section('content')
<div class="bg-white rounded-lg shadow-md p-8 max-w-xl mx-auto">
    <div class="text-center mb-6">
        <div class="bg-red-100 rounded-full p-3 inline-block mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Ward Access Denied</h2>
        <p class="text-gray-600 mb-6">
            You do not have access to the selected ward. Please select a ward you have been assigned to.
        </p>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <h3 class="text-lg font-medium text-gray-800 mb-2">What happened?</h3>
        <p class="text-gray-600 mb-3">
            You tried to access a ward that you have not been assigned to. This could be because:
        </p>
        <ul class="list-disc ml-6 text-gray-600 space-y-2">
            <li>Your account doesn't have access to this particular ward</li>
            <li>There might be a problem with your ward assignment</li>
            <li>The selected ward may no longer exist in the system</li>
        </ul>
    </div>

    <div class="flex justify-center space-x-4">
        <a href="{{ route('ward.select') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
            Select a Different Ward
        </a>
        <a href="{{ route('support.access-control') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition">
            View Access Guide
        </a>
    </div>
</div>
@endsection
