<div wire:poll.500ms>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                &nbsp;
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Start
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                End
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Position
            </th>
            <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created on
            </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @forelse($jobs as $job)
            <tr>
                <td class="py-4 text-center align-middle whitespace-nowrap">
                    <x-job-status-icon :job="$job"/>
                </td>
                <td class="px-6 pb-2 whitespace-nowrap">
                    <x-job-status :job="$job"/>
                </td>
                <td class="px-6 pb-2 whitespace-nowrap">{{ $job->getStart()->format('D d M Y') }}</td>
                <td class="px-6 pb-2 whitespace-nowrap">{{ $job->getEnd()->format('D d M Y') }}</td>
                <td class="px-6 pb-2 whitespace-nowrap">{{ $job->getPosition() }}</td>
                <td class="px-6 pb-2 whitespace-nowrap">{{ $job->getCreatedAt()->toDayDateTimeString() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center">No jobs yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
