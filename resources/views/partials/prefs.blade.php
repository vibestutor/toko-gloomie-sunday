{{-- Floating Language + Currency Selector --}}
<div class="fab-prefs" id="fab-prefs">
  <button class="fab-prefs__btn" id="fab-prefs-btn" aria-expanded="false" aria-controls="fab-prefs-sheet">
    <i class="fa-solid fa-earth-asia"></i>
    <span class="fab-prefs__current">
      {{ strtoupper(app()->getLocale()) }} · {{ session('currency','IDR') }}
    </span>
    <i class="fa-solid fa-chevron-up fab-prefs__chev"></i>
  </button>

  <div class="fab-prefs__sheet" id="fab-prefs-sheet" hidden>
    <div class="fab-prefs__head">
      <strong>Language & Currency</strong>
      <button type="button" class="fab-prefs__close" id="fab-prefs-close" aria-label="Close">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    {{-- form real yang dipost ke backend --}}
    <form id="fab-pref-form" action="{{ route('pref.update') }}" method="POST">
      @csrf
      <input type="hidden" name="locale"   id="fab-locale">
      <input type="hidden" name="currency" id="fab-currency">
    </form>

    <div class="fab-prefs__list" id="fab-prefs-list">
      {{-- Auto --}}
      <button type="button" class="fab-prefs__opt" data-auto="1">
        <span>🌍 Auto Location</span>
      </button>

      {{-- Opsi cepat yang kamu minta --}}
      <button type="button" class="fab-prefs__opt" data-locale="id" data-currency="IDR">
        🇮🇩 Indonesian · IDR
      </button>
      <button type="button" class="fab-prefs__opt" data-locale="en" data-currency="USD">
        🇺🇸 English · USD
      </button>
      <button type="button" class="fab-prefs__opt" data-locale="ms" data-currency="MYR">
        🇲🇾 Bahasa Melayu · MYR
      </button>
      <button type="button" class="fab-prefs__opt" data-locale="th" data-currency="THB">
        🇹🇭 ไทย · THB
      </button>
      <button type="button" class="fab-prefs__opt" data-locale="ms-BN" data-currency="BND">
  🇧🇳 Brunei · BND
</button>

      {{-- Tambahan opsional (boleh hapus kalau belum perlu) --}}
      <button type="button" class="fab-prefs__opt" data-locale="ja" data-currency="JPY">🇯🇵 日本語 · JPY</button>
      <button type="button" class="fab-prefs__opt" data-locale="ko" data-currency="KRW">🇰🇷 한국어 · KRW</button>
      <button type="button" class="fab-prefs__opt" data-locale="de" data-currency="EUR">🇩🇪 Deutsch · EUR</button>
      <button type="button" class="fab-prefs__opt" data-locale="fr" data-currency="EUR">🇫🇷 Français · EUR</button>
    </div>
  </div>
</div>
