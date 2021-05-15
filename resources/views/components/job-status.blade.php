@if ($job->wasCreated())
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border border-gray-300">
        Created
    </span>
@elseif(($job->isProcessing()))
    <div class="relative pt-4">
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
            <div style="width:30%"
                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
        </div>
    </div>
@elseif($job->hasFinished())
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        Success
    </span>
@elseif($job->hasFailed())
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
        Failed
    </span>
@else
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
        Unknown
    </span>
@endif
