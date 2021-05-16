@if ($job->wasCreated())
    &nbsp;
@elseif(($job->isProcessing()))
    <span class="material-icons-outlined text-indigo-600 animate-spin transform rotate-180">sync</span>
@elseif($job->hasFinished())
    <span class="material-icons text-green-700">check_circle</span>
@elseif($job->hasFailed())
    <span class="material-icons text-red-800">cancel</span>
@else
    <span class="material-icons text-yellow-500">help</span>
@endif
