@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

@push('javascript')
    <script>
        var startPicker = new Pikaday({
            field: document.getElementById('start-date'),
            firstDay: 1,
            disableDayFn: function (date) {
                if (date.getDay() > 0) {
                    return true;
                }
                let now = new Date();
                now.setHours(0, 0, 0, 0);
                if (now.getTime() > date.getTime()) {
                    return true;
                }
                return false;
            }
        });
        var endPicker = new Pikaday({
            field: document.getElementById('end-date'),
            firstDay: 1,
            disableDayFn: function (date) {
                if (date.getDay() > 0) {
                    return true;
                }
                let startDate = startPicker.getDate();
                if (null === startDate) {
                    startDate = new Date();
                }
                startDate.setHours(0, 0, 0, 0);
                if (startDate.getTime() > date.getTime()) {
                    return true;
                }
                return false;
            }
        });
    </script>
@endpush

<x-layout>
    <x-slot name="title">Automatically generate your shifts events</x-slot>
    <x-slot name="intro">
        <p class="text-left">
            Please choose the date for the first shift you want to generate and specify its position in the
            rota file. Then choose the date for the last shift. Note that both dates must be a Sunday.
        </p>
        <p class="mt-4 text-left">
            For your convenience the current rota starts on {{ $rotaStartDate->format('d/m/y') }}, there are {{ $lastPosition }}
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

    <form method="post" action="{{ route('generate-events') }}">
        @csrf
        <div class="flex w-full justify-center space-x-10">
            <div>
                <label for="start-date" class="block font-medium text-sm text-gray-700">Start date</label>
                <input name="start-date" type="text" id="start-date"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("start-date")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                <input name="position" type="text" value="{{ old('position') }}"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("position")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end-date" class="block font-medium text-sm text-gray-700">End date</label>
                <input name="end-date" type="text" id="end-date"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("end-date")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-8 flex w-full justify-center space-x-5">
            <button type="button" onclick="window.location='{{route('home')}}'"
                    class="items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring-blue active:text-gray-800 active:bg-gray-50 transition">
                Cancel
            </button>
            <button type="submit"
                    class="items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring-gray disabled:opacity-25 transition">
                Generate
            </button>
        </div>
    </form>
</x-layout>
