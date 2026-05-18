@props([
    'title',
    'id' => null,
    'as' => 'h2',
    'wrapperClass' => 'text-center mb-4 sm:mb-6 md:mb-8 lg:mb-12',
    'titleClass' => 'text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#017249] tracking-wider',
    'titleStyle' => "font-family: 'Bizon', sans-serif;",
])

<header class="{{ $wrapperClass }}">
    <{{ $as }} @if($id) id="{{ $id }}" @endif class="{{ $titleClass }}" style="{{ $titleStyle }}">
        {{ $title }}
    </{{ $as }}>
</header>
