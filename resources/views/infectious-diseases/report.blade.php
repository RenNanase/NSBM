@extends('layouts.app')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <div class="flex justify-between mb-8">
        <h3 class="text-lg font-medium leading-6" style="color: var(--color-text-primary);">Infectious Disease Report</h3>
        <div class="flex space-x-3">
            <a href="{{ route('infectious-diseases.index') }}" class="inline-flex items-center px-3 py-2 border rounded-md text-sm leading-4 font-medium" style="border-color: var(--color-border); color: var(--color-text-primary); background-color: var(--color-bg-alt);">
                Back to List
            </a>
            <button onclick="window.print()" class="btn btn-secondary inline-flex items-center px-3 py-2 rounded-md shadow-sm text-sm leading-4 font-medium text-white">
                Print Report
            </button>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('infectious-diseases.report') }}" method="GET" class="mb-6 flex gap-4">
            <div class="w-48">
                <label for="date_from" class="block text-sm font-medium" style="color: var(--color-text-secondary);">Date From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
            </div>
            <div class="w-48">
                <label for="date_to" class="block text-sm font-medium" style="color: var(--color-text-secondary);">Date To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border: 1px solid var(--color-border); color: var(--color-text-primary);">
            </div>
            <div class="self-end">
                <button type="submit" class="btn btn-primary inline-flex items-center px-3 py-2 rounded-md shadow-sm text-sm leading-4 font-medium text-white">
                    Filter
                </button>
                <a href="{{ route('infectious-diseases.report') }}" class="ml-2 inline-flex items-center px-3 py-2 border rounded-md text-sm leading-4 font-medium" style="border-color: var(--color-border); color: var(--color-text-primary); background-color: var(--color-bg-alt);">
                    Reset
                </a>
            </div>
        </form>

        <div class="dashboard-card p-6 rounded-lg mb-8">
            <h4 class="font-medium text-lg mb-4" style="color: var(--color-text-primary);">Summary by Disease</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                    <thead style="background-color: var(--color-secondary-dark); color: var(--color-text-inverse);">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Disease</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Adult</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Paediatric</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Neonate</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--color-border);">
                        @foreach($diseaseReport as $disease => $data)
                        <tr class="hover:bg-opacity-20 transition duration-150" style="background-color: var(--color-table-stripe); --tw-bg-opacity: 0.5;">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">{{ $disease }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['adult'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['paed'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['neonate'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                        <tr style="background-color: var(--color-bg-alt);">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['adult'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['paed'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['neonate'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['total'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card p-6 rounded-lg mb-8">
            <h4 class="font-medium text-lg mb-4" style="color: var(--color-text-primary);">Summary by Ward</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: var(--color-border);">
                    <thead style="background-color: var(--color-secondary-dark); color: var(--color-text-inverse);">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ward</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Adult</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Paediatric</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Neonate</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--color-border);">
                        @foreach($wardReport as $wardName => $data)
                        <tr class="hover:bg-opacity-20 transition duration-150" style="background-color: var(--color-table-stripe); --tw-bg-opacity: 0.5;">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">{{ $wardName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['adult'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['paed'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-secondary);">{{ $data['neonate'] ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">{{ $data['total'] }}</td>
                        </tr>
                        @endforeach
                        <tr style="background-color: var(--color-bg-alt);">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['adult'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['paed'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['neonate'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" style="color: var(--color-text-primary);">{{ $totals['total'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .px-4, .px-4 * {
            visibility: visible;
        }
        .px-4 {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        form, button, a {
            display: none !important;
        }
        table, th, td {
            color: black !important;
            border-color: #ddd !important;
        }
        th {
            background-color: #f3f4f6 !important;
        }
    }
</style>
@endsection
