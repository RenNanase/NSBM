@extends('layout')

@section('title', 'Access Denied - NSBM')
@section('header', 'Access Denied')

@section('content')
<div class="bg-white rounded-lg shadow-md p-8">
    <div class="text-center mb-6">
        <div class="bg-red-100 rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Access Denied</h2>
        <p class="text-gray-600 mb-6">
            You do not have access to the delivery features.
            Only maternity ward staff are authorized to access this section.
        </p>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        If you believe you should have access to this feature, please contact your administrator.
                    </p>
                </div>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
            Return to Dashboard
        </a>
    </div>
</div>
@endsection
