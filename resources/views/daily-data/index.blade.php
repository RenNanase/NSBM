@extends('layout')

@section('title', 'Daily Data - NSBM')
@section('header', 'Daily Data')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="daily-data-filters mb-4">
        <form action="{{ route('daily-data.index') }}" method="GET" class="flex items-center space-x-4">
            @if(Auth::user()->isAdmin())
            <div class="flex-1">
                <label for="ward_id" class="block mb-1 text-sm font-medium" style="color: var(--color-text-secondary);">Select Ward</label>
                <select id="ward_id" name="ward_id" class="w-full p-2 border rounded" style="border-color: var(--color-border);" onchange="this.form.submit()">
                    <option value="">All Wards</option>
                    @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ request('ward_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex-1">
                <label for="filter_date" class="block mb-1 text-sm font-medium" style="color: var(--color-text-secondary);">Filter by Date</label>
                <input type="date" id="filter_date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                    class="w-full p-2 border rounded" style="border-color: var(--color-border);">
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter mr-2"></i> Apply Filter
                </button>
            </div>
        </form>

        <div class="date-navigation">
            <a href="{{ route('daily-data.index', ['date' => now()->format('Y-m-d'), 'ward_id' => request('ward_id')]) }}"
                class="date-nav-item {{ request('date', now()->format('Y-m-d')) == now()->format('Y-m-d') ? 'active' : '' }}">
                Today
            </a>
            <a href="{{ route('daily-data.index', ['date' => now()->subDay()->format('Y-m-d'), 'ward_id' => request('ward_id')]) }}"
                class="date-nav-item {{ request('date') == now()->subDay()->format('Y-m-d') ? 'active' : '' }}">
                Yesterday
            </a>
            <a href="{{ route('daily-data.index', ['date' => now()->subDays(2)->format('Y-m-d'), 'ward_id' => request('ward_id')]) }}"
                class="date-nav-item {{ request('date') == now()->subDays(2)->format('Y-m-d') ? 'active' : '' }}">
                Previous Day
            </a>
            @if(request('date') && request('date') < now()->format('Y-m-d'))
                <a href="{{ route('daily-data.index', ['date' => \Carbon\Carbon::parse(request('date'))->addDay()->format('Y-m-d'), 'ward_id' => request('ward_id')]) }}"
                    class="date-nav-item">
                    Next Day
                </a>
            @endif
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold" style="color: var(--color-secondary);">
            Daily Data for {{ \Carbon\Carbon::parse(request('date', now()->format('Y-m-d')))->format('d M Y') }}
            @if(isset($ward) && $ward)
                - {{ $ward->name }}
            @elseif(Auth::user()->isAdmin() && !request('ward_id'))
                - All Wards
            @endif
        </h2>
        <a href="{{ route('daily-data.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-2"></i> Add New Entry
        </a>
    </div>

    @if($dailyData->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <p class="text-lg mb-4">No daily data entries recorded for this date.</p>
            <a href="{{ route('daily-data.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-2"></i> Record First Entry
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full daily-data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        @if(Auth::user()->isAdmin() && (!isset($ward) || !$ward))
                        <th>Ward</th>
                        @endif
                        <th>Deaths</th>
                        <th>Neonatal Jaundice</th>
                        <th>Bedridden Cases</th>
                        <th>Incident Reports</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyData as $entry)
                        <tr>
                            <td>{{ $entry->date->format('d M Y') }}</td>
                            @if(Auth::user()->isAdmin() && (!isset($ward) || !$ward))
                            <td>{{ $entry->ward->name ?? 'Unknown' }}</td>
                            @endif
                            <td>{{ $entry->death }}</td>
                            <td>{{ $entry->neonatal_jaundice }}</td>
                            <td>{{ $entry->bedridden_case }}</td>
                            <td>{{ $entry->incident_report }}</td>
                            <td>{{ $entry->user->username }}</td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('daily-data.show', $entry->id) }}" class="btn-icon text-secondary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('daily-data.edit', $entry->id) }}" class="btn-icon text-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <form action="{{ route('daily-data.destroy', $entry->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this entry?');">
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
        <div class="mt-4">
            {{ $dailyData->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
