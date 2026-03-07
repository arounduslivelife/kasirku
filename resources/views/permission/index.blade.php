@extends('layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pengaturan Otoritas Role</h1>
    <p class="mt-2 text-sm text-gray-600">Atur kewenangan akses fitur untuk masing-masing tingkatan pengguna (Role).</p>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('permission.update') }}" method="POST">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Modul & Fitur</th>
                        @foreach($roles as $role)
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            {{ ucfirst($role) }}
                        </th>
                        @endforeach
                        <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32 bg-indigo-50/50">
                            Owner (Full)
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($permissions as $module => $modulePermissions)
                    <tr class="bg-gray-50/30">
                        <td colspan="{{ count($roles) + 2 }}" class="py-2 pl-6 pr-3 text-xs font-bold text-indigo-600 uppercase tracking-wider">
                            {{ $module }}
                        </td>
                    </tr>
                    @foreach($modulePermissions as $permission)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="py-4 pl-10 pr-3 text-sm text-gray-900">
                            <div class="font-medium">{{ $permission->display_name }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $permission->name }}</div>
                        </td>
                        @foreach($roles as $role)
                        <td class="px-3 py-4 text-center">
                            <input type="checkbox" 
                                   name="permissions[{{ $role }}][]" 
                                   value="{{ $permission->id }}"
                                   class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 transition-all cursor-pointer"
                                   {{ in_array($permission->id, $assignments[$role] ?? []) ? 'checked' : '' }}>
                        </td>
                        @endforeach
                        <td class="px-3 py-4 text-center bg-indigo-50/20">
                            <input type="checkbox" checked disabled class="h-5 w-5 rounded border-gray-300 text-gray-400 bg-gray-100 cursor-not-allowed">
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan Otoritas
            </button>
        </div>
    </div>
</form>

<div class="mt-8 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-xs text-amber-700">
                <strong>Catatan:</strong> Perubahan otoritas akan langsung berdampak pada menu sidebar dan akses halaman bagi user yang sedang aktif setelah mereka melakukan refresh halaman. Role <strong>Owner</strong> selalu memiliki akses penuh ke seluruh fitur sistem.
            </p>
        </div>
    </div>
</div>
@endsection
