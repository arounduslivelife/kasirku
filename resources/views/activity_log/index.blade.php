@extends('layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Log Aktivitas Pengguna</h1>
    <p class="mt-2 text-sm text-gray-600">Pantau seluruh jejak tindakan yang dilakukan oleh pengguna di dalam sistem.</p>
</div>

<div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Waktu</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Pengguna</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi Detail</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white text-sm">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-gray-500">
                        {{ $log->created_at->format('d M Y, H:i:s') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 font-medium text-gray-900">
                        @if($log->user)
                            {{ $log->user->name }}
                        @else
                            <span class="text-gray-400 italic">System / Deleted User</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4">
                        @if($log->action === 'CREATED')
                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">CREATE</span>
                        @elseif($log->action === 'UPDATED')
                            <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">UPDATE</span>
                        @elseif($log->action === 'DELETED')
                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">DELETE</span>
                        @elseif($log->action === 'LOGIN' || $log->action === 'LOGOUT')
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">{{ $log->action }}</span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">{{ $log->action }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-4 text-gray-600 break-words max-w-sm">
                        {{ $log->description }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-gray-400 font-mono text-xs">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p class="text-sm font-medium">Belum ada aktivitas yang terekam.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
