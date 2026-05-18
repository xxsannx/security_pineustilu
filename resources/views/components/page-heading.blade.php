@props([
'title',
'subtitle' => null,
'id' => null,
'wrapperClass' => 'text-center py-10',
'titleClass' => 'text-4xl md:text-5xl font-extrabold text-brand-primary tracking-wider',
'titleStyle' => "font-family: Bizon, sans-serif;",
'subtitleClass' => 'mt-3 text-sm md:text-base text-[#0b5a3e] max-w-2xl mx-auto',
'aos' => 'fade-down',
'aosDuration' => '800',
'aosDelay' => null,
])

<header class="{{ $wrapperClass }}" data-aos="{{ $aos }}" data-aos-duration="{{ $aosDuration }}" @if($aosDelay) data-aos-delay="{{ $aosDelay }}" @endif>
    <h1
    @if($id)
    id="{{ $id }}"
    @endif
    class="{{ $titleClass }} title-custom"
    lang="id"
    >
        {{ $title }}
    </h1>

    @if($subtitle)
    <p class="{{ $subtitleClass }}">
        {{ $subtitle }}
    </p>
    @endif

    <style>
    .title-custom {
        font-family: Bizon, sans-serif;
    }
    </style>
</header>