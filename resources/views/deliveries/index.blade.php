@extends('layout')

@section('title', 'Delivery Records - NSBM')
@section('header', 'Delivery Records')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold font-['Noto_Sans_JP'] uppercase">{{ $ward->name }} - Delivery Records</h3>
        <a href="{{ route('delivery.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md text-sm">
            Add New Record
        </a>
    </div>

    @if($deliveries->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-lg">No delivery records found.</p>
            <p class="mt-2">Start by adding a new delivery record.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-black text-white text-sm uppercase">
                        <th class="py-3 px-4 text-center">Date</th>
                        <th class="py-3 px-4 text-center">Updated By</th>
                        <th class="py-3 px-4 text-center">Total</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($deliveries as $delivery)
                    <tr class="hover:bg-pink-50">
                        <td class="py-3 px-4 text-center">{{ $delivery->report_date->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-center">{{ $delivery->user->username }}</td>
                        <td class="py-3 px-4 text-center font-bold">{{ $delivery->total }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('delivery.show', $delivery) }}" class="bg-blue-100 text-blue-800 hover:bg-blue-200 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                @if(Auth::user()->username === 'admin')
                                <form action="{{ route('delivery.destroy', $delivery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-800 hover:bg-red-200 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>
@endsection
