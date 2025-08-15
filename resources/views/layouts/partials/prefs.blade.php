@php
  $variant = $variant ?? 'inline';
  $cur = session('currency','IDR');
  $loc = app()->getLocale();
@endphp

<form class="prefs {{ $variant === 'mobile' ? 'prefs--mobile' : 'prefs--inline' }}"
      id="pref-form-{{ $variant }}"
      action="{{ route('pref.update') }}"
      method="POST">
  @csrf
  <label class="prefs-label" for="locale-{{ $variant }}">🌐</label>
  <select name="locale" id="locale-{{ $variant }}" class="prefs-select">
    <option value="id" {{ $loc==='id'?'selected':'' }}>Indonesia</option>
    <option value="en" {{ $loc==='en'?'selected':'' }}>English</option>
    <option value="ja" {{ $loc==='ja'?'selected':'' }}>日本語</option>
    <option value="ko" {{ $loc==='ko'?'selected':'' }}>한국어</option>
    <option value="de" {{ $loc==='de'?'selected':'' }}>Deutsch</option>
    <option value="fr" {{ $loc==='fr'?'selected':'' }}>Français</option>
    <option value="es" {{ $loc==='es'?'selected':'' }}>Español</option>
  </select>

  <select name="currency" id="currency-{{ $variant }}" class="prefs-select">
    <option value="IDR" {{ $cur==='IDR'?'selected':'' }}>IDR</option>
    <option value="USD" {{ $cur==='USD'?'selected':'' }}>USD</option>
    <option value="JPY" {{ $cur==='JPY'?'selected':'' }}>JPY</option>
    <option value="KRW" {{ $cur==='KRW'?'selected':'' }}>KRW</option>
    <option value="EUR" {{ $cur==='EUR'?'selected':'' }}>EUR</option>
    <option value="GBP" {{ $cur==='GBP'?'selected':'' }}>GBP</option>
  </select>
</form>
