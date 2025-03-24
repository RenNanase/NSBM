@extends('layout')

@section('title', 'Delivery Records - NSBM')
@section('header', 'Delivery Records')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold" style="color: var(--color-secondary);">
        {{ $ward->name }} - Delivery Records
    </h2>
    <a href="{{ route('delivery.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle mr-2"></i> Add New Record
    </a>
</div>

<div class="dashboard-card p-6 mb-6">
    @if($deliveries->isEmpty())
        <div class="text-center py-8" style="color: var(--color-text-secondary);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-text-secondary);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-lg">No delivery records found.</p>
            <p class="mt-2">Start by adding a new delivery record.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr style="background-color: var(--color-secondary-dark); color: var(--color-text-inverse);" class="text-sm uppercase">
                        <th class="py-3 px-4 text-center">Date</th>
                        <th class="py-3 px-4 text-center">Updated By</th>
                        <th class="py-3 px-4 text-center">Total</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody style="color: var(--color-text-primary);" class="divide-y" style="border-color: var(--color-border);">
                    @foreach($deliveries as $delivery)
                    <tr class="hover:bg-opacity-10 hover:bg-primary">
                        <td class="py-3 px-4 text-center">{{ $delivery->report_date->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-center">{{ $delivery->user->username }}</td>
                        <td class="py-3 px-4 text-center font-bold" style="color: var(--color-primary);">{{ $delivery->total }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('delivery.show', $delivery) }}" class="btn-icon text-secondary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('delivery.edit', $delivery) }}" class="btn-icon text-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(Auth::user()->username === 'admin')
                                <form action="{{ route('delivery.destroy', $delivery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-accent" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
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
        <div class="mt-6" style="color: var(--color-text-primary);">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>
@endsection
