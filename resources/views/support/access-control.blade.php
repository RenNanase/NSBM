@extends('layout')

@section('title', 'Access Control Guide - NSBM')
@section('header', 'Access Control Guide')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H4m12-4a4 4 0 01-4-4m0 0a4 4 0 01-4 4m4-4v12" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium">Understanding Access Control</h3>
                <p class="text-sm text-gray-600">How permissions work in the Nursing Service Bed Management System</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Introduction -->
        <div>
            <h3 class="text-xl font-medium text-gray-800 mb-3">Introduction</h3>
            <p class="text-gray-600">
                The NSBM system uses a role-based access control system to ensure that users can only access the features
                and data they need for their specific roles. This guide explains how access is managed within the system.
            </p>
        </div>

        <!-- Ward Assignment -->
        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Ward Assignment</h3>
            <p class="text-gray-600 mb-4">
                Each user is assigned to one or more specific wards. Users can only view and manage data for the wards
                they are assigned to. When you log in, you must select which ward you want to work with.
            </p>
            <div class="flex items-start space-x-2 bg-white p-3 rounded border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-700">
                    If you need access to additional wards, please contact your system administrator.
                </p>
            </div>
        </div>

        <!-- Special Features -->
        <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
            <h3 class="text-lg font-medium text-blue-800 mb-3">Special Features Access</h3>
            <p class="text-blue-700 mb-4">
                Some features in the system are only available to users assigned to specific ward types:
            </p>
            <ul class="space-y-3 ml-5 list-disc text-blue-700">
                <li>
                    <span class="font-medium">Delivery Features:</span> Only accessible to users assigned to maternity-related wards
                    (wards with names containing MATERNITY, LABOUR, DELIVERY, or OB-GYN).
                </li>
                <li>
                    <span class="font-medium">Future Features:</span> Additional specialized features may be added that are restricted
                    to specific ward types.
                </li>
            </ul>
        </div>

        <!-- Admin Access -->
        <div class="bg-amber-50 rounded-lg p-5 border border-amber-200">
            <h3 class="text-lg font-medium text-amber-800 mb-3">Administrator Privileges</h3>
            <p class="text-amber-700 mb-4">
                Administrators have additional privileges including:
            </p>
            <ul class="space-y-3 ml-5 list-disc text-amber-700">
                <li>Editing entries created by other users</li>
                <li>Deleting records</li>
                <li>Accessing system configuration</li>
                <li>Managing user accounts and ward assignments</li>
            </ul>
        </div>

        <!-- Access Denied -->
        <div class="bg-red-50 rounded-lg p-5 border border-red-200">
            <h3 class="text-lg font-medium text-red-800 mb-3">Access Denied Messages</h3>
            <p class="text-red-700 mb-4">
                If you attempt to access a feature or page you don't have permission for, you'll see an access denied message.
                These messages help protect the integrity of the system and ensure that users only work with data they're
                responsible for.
            </p>
            <div class="flex items-start space-x-2 bg-white p-3 rounded border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-gray-700">
                    If you believe you should have access to a feature but are receiving an access denied message,
                    please contact your system administrator.
                </p>
            </div>
        </div>

        <!-- Support -->
        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Need Help?</h3>
            <p class="text-gray-600 mb-4">
                If you need assistance with access to specific features or wards, please contact your system administrator.
            </p>
            <div class="flex justify-center">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                    Return to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
