@props(['route', 'icon'])

<a href="{{ route($route) }}" 
   {{ $attributes->merge(['class' => 'flex items-center px-4 py-3 rounded-xl transition-all duration-300 font-bold text-[11px] uppercase tracking-wider ' . 
   (request()->routeIs($route) ? 'bg-blue-600/10 text-blue-500 border-l-4 border-blue-600 shadow-[inset_0_0_15px_rgba(37,99,235,0.1)]' : 'text-gray-500 hover:bg-white/5 hover:text-white')]) }}>
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
    </svg>
    {{ $slot }}
</a>