<x-layout>
    <x-slot name="title">Automatically generate your shifts events</x-slot>
    <x-slot name="intro">
        <p class="text-left">
            Please choose the date for the first shift you want to generate and specify its position in the
            rota file. Then choose the date for the last shift. Note that both dates must be a Sunday.
        </p>
        <p class="mt-4 text-left">
            For your convenience the current rota starts on {{ $rotaStartDate->format('d/m/Y') }}, there are {{ $lastPosition }}
            positions and the shifts for position 1 are:
        </p>

        <div class="mt-4 py-2 align-middle inline-block justify-center px-2">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-3">Sun</th>
                        <th scope="col" class="px-6 py-3">Mon</th>
                        <th scope="col" class="px-6 py-3">Tue</th>
                        <th scope="col" class="px-6 py-3">Wed</th>
                        <th scope="col" class="px-6 py-3">Thu</th>
                        <th scope="col" class="px-6 py-3">Fri</th>
                        <th scope="col" class="px-6 py-3">Sat</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="whitespace-nowrap text-sm text-gray-500">
                        @if($firstWeek->isLeaveCover())
                            <td colspan="7" class="px-6 py-4">Leave cover</td>
                        @else
                            @foreach($firstWeek->getDuties() as $duty)
                                <td class="px-6 py-4">@if($duty->isRestDay())Rest @else {{ $duty->getStart() }}
                                    <br>{{ $duty->getEnd() }} @endif</td>
                            @endforeach
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </x-slot>
    <livewire:generate-events />
</x-layout>
