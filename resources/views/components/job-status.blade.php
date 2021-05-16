@if ($job->wasCreated())
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border border-gray-300">
        Created
    </span>
@elseif($job->isWaiting())
    <div class="flex flex-wrap">
        <p class="text-indigo-500 test-xs">Waiting</p>
        <div class="ml-5 mt-3 dot-flashing"></div>
    </div>
@elseif($job->isGenerating())
    <div class="relative pt-4">
        <p class="text-indigo-500 test-xs mb-0.5">Generating</p>
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
            <div style="width:{{ $job->generatingProgress() }}%"
                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
        </div>
    </div>
@elseif($job->isSending())
    <div class="relative pt-4">
        <p class="text-indigo-500 test-xs mb-0.5">Sending</p>
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
            <div style="width:{{ $job->sendingProgress() }}%"
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
