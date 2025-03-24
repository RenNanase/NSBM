@props(['dailyData' => null, 'ward' => null])

<div class="bg-primary-light rounded-lg overflow-hidden" style="border: 1px solid var(--color-border);">
    <div class="px-4 py-3 bg-secondary" style="color: white;">
        <h3 class="text-lg font-medium">Daily Health Statistics</h3>
        @if($dailyData)
            <p class="text-xs">{{ $dailyData->date->format('d M Y') }} - {{ $ward->name }}</p>
        @else
            <p class="text-xs">Today's Summary</p>
        @endif
    </div>

    @if($dailyData)
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="stat-value">{{ $dailyData->death }}</div>
                    <div class="stat-label">Deaths</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">{{ $dailyData->neonatal_jaundice }}</div>
                    <div class="stat-label">Neonatal Jaundice</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">{{ $dailyData->bedridden_case }}</div>
                    <div class="stat-label">Bedridden Cases</div>
                </div>

                <div class="stat-card">
                    <div class="stat-value">{{ $dailyData->incident_report }}</div>
                    <div class="stat-label">Incident Reports</div>
                </div>
            </div>

            @if(!empty($dailyData->remarks))
                <div class="mt-4 p-3 bg-white rounded-md" style="border: 1px solid var(--color-border);">
                    <div class="text-sm font-medium mb-1" style="color: var(--color-text-secondary);">Remarks:</div>
                    <div class="text-sm" style="color: var(--color-text-primary);">{{ $dailyData->remarks }}</div>
                </div>
            @endif

            <div class="mt-4 text-right">
                <a href="{{ route('daily-data.show', $dailyData->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye mr-1"></i> View Details
                </a>
            </div>
        </div>
    @else
        <div class="p-4">
            <div class="empty-state py-6">
                <div class="empty-state-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <p class="mb-4">No data recorded for today</p>
                <a href="{{ route('daily-data.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-2"></i> Record Data
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
}

.stat-card {
    background-color: white;
    border-radius: 0.375rem;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-secondary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
}
</style>
