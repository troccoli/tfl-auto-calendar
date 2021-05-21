<x-layout>
    <x-slot name="title">A better way to organise your work life</x-slot>
    <x-slot name="intro">On this site you will be able to automatically add events to your Google calendar for your
        shifts.
    </x-slot>

    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
        <div class="relative">
            <dt>
                <a href="{{ route('generate-events') }}"
                   class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <span class="material-icons-outlined h-6 w-6">play_arrow</span>
                </a>
                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Generate events</p>
            </dt>
            <dd class="mt-2 ml-16 text-base text-gray-500">
                This is where you go when you are ready to create your shift on your Google calendar. You will
                be asked the start date and the position on the rota file.
            </dd>
        </div>

        <div class="relative">
            <dt>
                <a href="{{ route('dashboard') }}"
                   class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <span class="material-icons-outlined h-6 w-6">dashboard</span>
                </a>
                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Dashboard</p>
            </dt>
            <dd class="mt-2 ml-16 text-base text-gray-500">
                Here you can check on the progress of every jobs.
            </dd>
        </div>

        <div class="relative">
            <dt>
                <a href="{{ route('holidays') }}"
                   class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                    <span class="material-icons-outlined h-6 w-6">card_travel</span>
                </a>
                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Holidays</p>
            </dt>
            <dd class="mt-2 ml-16 text-base text-gray-500">
                Add your holiday periods.
            </dd>
        </div>
    </dl>
</x-layout>
