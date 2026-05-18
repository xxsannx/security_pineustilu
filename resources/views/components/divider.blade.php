@php
	$baseClass = $baseClass ?? 'border-t border-brand-primary/20 mx-0';
	$class = $class ?? '';
	$id = $id ?? null;
	$style = $style ?? null;
	$ariaHidden = $ariaHidden ?? null;
@endphp

<hr
	class="{{ trim($baseClass . ' ' . $class) }}"
	@if($id) id="{{ $id }}" @endif
	@if($style) style="{{ $style }}" @endif
	@if($ariaHidden !== null) aria-hidden="{{ $ariaHidden }}" @endif
>
