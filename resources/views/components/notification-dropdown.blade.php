@php
    use App\Models\Notification;
    $unreadCount = Notification::where('is_read', false)->count();
@endphp

<div class="relative">
    <a href="{{ url('/admin/notifications') }}"
       class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none focus:bg-gray-50 focus:text-gray-900 transition ease-in-out duration-150">

        {{-- Icon lonceng notifikasi (selalu tampil) --}}
        <svg class="w-8 h-8 {{ $unreadCount > 0 ? 'text-white' : 'text-white' }}"
             fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z" />
            <path d="M10 18a2 2 0 002-2H8a2 2 0 002 2z" />
        </svg>

        {{-- Badge merah jumlah notifikasi --}}
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 translate-x-1/4 -translate-y-1/4 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-semibold leading-none text-white bg-red-600 rounded-full shadow-md">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </a>
</div>
