<div>
    <div>
        <div class="flex w-full justify-center space-x-10">
            <div x-data
                 x-init="new Pikaday({
                    field: $refs.startDate,
                    firstDay: 1,
                    minDate: new Date(),
                    disableDayFn: function(date) {
                        if (date.getDay() > 0) {
                            return true;
                        }
                        return false;
                    },
                })"
            >
                <label for="start-date" class="block font-medium text-sm text-gray-700">Start date</label>
                <input name="start-date" type="text" id="start-date" x-ref="startDate" wire:model.lazy="startDate"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("startDate")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
                <input name="position" type="text" wire:model.lazy="position"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("position")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div x-data
                 x-init="new Pikaday({
                    field: $refs.endDate,
                    firstDay: 1,
                    minDate: new Date(),
                    disableDayFn: function (date) {
                        if (date.getDay() > 0) {
                            return true;
                        }
                        return false;
                    }
                })"
            >
                <label for="end-date" class="block font-medium text-sm text-gray-700">End date</label>
                <input name="end-date" type="text" id="end-date" x-ref="endDate" wire:model.lazy="endDate"
                       class="mt-2 p-2 block border border-gray-600 rounded form-input rounded-md shadow-sm"/>
                @error("endDate")
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mt-8 flex w-full justify-center space-x-5">
            <button type="button" onclick="window.location='{{route('home')}}'"
                    class="items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring-blue active:text-gray-800 active:bg-gray-50 transition">
                Cancel
            </button>
            <button type="submit" wire:click="generateEvents"
                    class="items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring-gray disabled:opacity-25 transition">
                Generate
            </button>
        </div>
    </div>
</div>
