@props(['messages'])

@if ($messages)
    @php
        $customClass = $attributes->get('class', '');
        $hasTextColor = (bool) preg_match('/text-(red|rose|amber|yellow|white|slate|gray|blue|emerald)-\d+/', $customClass) || str_contains($customClass, 'text-white');
        $colorClass = $hasTextColor ? '' : 'text-rose-600';
    @endphp
    <ul {{ $attributes->merge(['class' => 'text-xs font-medium space-y-1 ' . $colorClass]) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
