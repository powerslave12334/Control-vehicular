@props(['route', 'label', 'icon'])
@php
    $active = request()->routeIs($route);
@endphp
<a href="{{ route($route) }}"
   class="w-full flex items-center gap-2.5 px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-left
          {{ $active ? 'bg-[#F71F96]/10 text-[#F71F96]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
    </svg>
    <span>{{ $label }}</span>
</a>
