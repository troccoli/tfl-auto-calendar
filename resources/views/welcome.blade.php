<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">TfL Auto Calendar</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                A better way to organise your work life
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                On this site you will be able to automatically add events to your Google calendar for your shifts.
            </p>
        </div>
        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                <div class="relative">
                    <dt>
                        <button
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="material-icons-outlined h-6 w-6">file_upload</span>
                        </button>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Upload new rota file</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        When the rota file changes, i.e. there is a new timetable, you will need to upload it first.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <button
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="material-icons-outlined h-6 w-6">add</span>
                        </button>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Generate events</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        This is where you go when you are ready to create your shift on your Google calendar. You will
                        be asked the start date and the position on the rota file.
                    </dd>
                </div>

                <div class="relative">
                    <dt>
                        <button
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <span class="material-icons-outlined h-6 w-6">restart_alt</span>
                        </button>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Restart a job</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        If something went wrong during the generation of the events you can try and start it again. This
                        process is clever enough to restart from where it left off.
                    </dd>
                </div>

                <div class="relative hidden">
                    <dt>
                        <div
                            class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            {{--                            <x-heroicons-o-annotation class="h-6 w-6" />--}}
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Mobile notifications</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit
                        eaque, iste dolor cupiditate blanditiis ratione.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-layout>
