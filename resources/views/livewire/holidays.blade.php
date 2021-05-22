@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

<div>
    <div class="flex justify-center">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start of 2 weeks period
                            </th>

                            <th scope="col" class="relative px-6 py-3 text-indigo-500 cursor-pointer"
                                x-data=""
                                x-init="new Pikaday({
                                    field: $refs.addCircle,
                                    firstDay: 1,
                                    minDate: new Date(),
                                    disableDayFn(date) {
                                        if (date.getDay() > 0) {
                                            return true;
                                        }
                                    },
                                    onSelect(date) {
                                        this.setDate('', true);
                                        const day = date.getDate();
                                        const month = date.getMonth() + 1;
                                        const year = date.getFullYear();
                                        $wire.addHoliday(`${year}-${month}-${day}`);
                                    }
                                })"
                            >
                                <span class="material-icons-outlined h-6 w-6" x-ref="addCircle">add_circle</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($holidays as $holiday)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap flex" colspan="2">
                                <div wire:click="deleteHoliday({{$holiday->getId()}})" class="text-red-600 hover:text-red-900 cursor-pointer">
                                    <span class="material-icons-outlined h-6 w-6">delete</span>
                                </div>
                                <span class="ml-2">{{ $holiday->getStart()->format('D d M Y') }} - {{ $holiday->getStart()->clone()->addWeeks(2)->subDay()->format('D d M Y') }}</span>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No holidays yet.</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">{{ $holidays->links() }}</div>
        </div>
    </div>
</div>
