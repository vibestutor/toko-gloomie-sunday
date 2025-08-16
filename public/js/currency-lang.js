(function () {
  // ====== CONFIG ======
  const LS = window.localStorage;
  const API_BASE = 'https://api.exchangerate.host';
  const RATE_TTL_MS = 30 * 60 * 1000; // 30 menit
  const PREF_URL =
    document.querySelector('meta[name="pref-url"]')?.content || '/pref';

  // Map locale pendek -> BCP47 buat Intl
  const LOCALE_MAP = {
    id: 'id-ID',
    'id-ID': 'id-ID',
    en: 'en-US',
    'en-US': 'en-US',
    ms: 'ms-MY',
    'ms-MY': 'ms-MY',
    'ms-BN': 'ms-BN',
    th: 'th-TH',
    'th-TH': 'th-TH',
    ja: 'ja-JP',
    'ja-JP': 'ja-JP',
    ko: 'ko-KR',
    'ko-KR': 'ko-KR',
    de: 'de-DE',
    'de-DE': 'de-DE',
    fr: 'fr-FR',
    'fr-FR': 'fr-FR',
  };

  const DEFAULT_CURRENCY_BY_LOCALE = {
    'id-ID': 'IDR',
    'en-US': 'USD',
    'ms-MY': 'MYR',
    'ms-BN': 'BND',
    'th-TH': 'THB',
    'ja-JP': 'JPY',
    'ko-KR': 'KRW',
    'de-DE': 'EUR',
    'fr-FR': 'EUR',
  };

  // ====== HELPERS ======
  const now = () => Date.now();
  const getJSON = (k) => {
    try { return JSON.parse(LS.getItem(k)); } catch { return null; }
  };
  const setJSON = (k, v) => LS.setItem(k, JSON.stringify(v));
  const parseDigits = (txt) => Number(String(txt || '').replace(/[^0-9]/g, '') || 0);

  function normalizeLocale(loc) {
    if (!loc) return 'id-ID';
    return LOCALE_MAP[loc] || loc;
  }

  function formatMoney(amount, currency, locale) {
    try {
      return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency,
        minimumFractionDigits: (currency === 'IDR' || currency === 'JPY' || currency === 'KRW') ? 0 : 2,
      }).format(Number(amount || 0));
    } catch {
      return `${currency} ${Number(amount || 0).toLocaleString(locale)}`;
    }
  }

  function setCookie(name, value, days){
  const d = new Date();
  d.setTime(d.getTime() + (days * 24*60*60*1000));
  document.cookie = `${name}=${encodeURIComponent(value)}; expires=${d.toUTCString()}; path=/`;
}


  // ====== RATE CACHE ======
  const memRate = {};
  async function getRate(from, to) {
    if (from === to) return 1;
    const key = `rate_${from}_${to}`;
    if (memRate[key]) return memRate[key];

    const cached = getJSON(key);
    if (cached && (now() - cached.t) < RATE_TTL_MS) {
      memRate[key] = cached.v;
      return cached.v;
    }

    try {
      const r = await fetch(`${API_BASE}/latest?base=${encodeURIComponent(from)}&symbols=${encodeURIComponent(to)}`);
      const j = await r.json();
      const v = j?.rates?.[to] ?? 1;
      memRate[key] = v;
      setJSON(key, { v, t: now() });
      return v;
    } catch {
      return 1;
    }
  }

 // ====== RENDER SEMUA HARGA ======
async function renderAll(currency, locale){
  const nodes = document.querySelectorAll('.price,[data-price]');
  const rate  = await getRate('IDR', currency);

  nodes.forEach(el => {
    // sumber base price: data-price / data-base-price
    let base = el.dataset.basePrice ?? el.dataset.price;

    if (base == null) {
      // fallback sekali dari teks (strip angka), lalu simpan
      const digits = String(el.textContent || '').replace(/[^0-9.]/g, '');
      base = digits ? Number(digits) : 0;
      el.dataset.basePrice = String(base);
    }

    const baseNum   = Number(base) || 0;
    const converted = baseNum * rate;

    el.textContent = formatMoney(converted, currency, locale);

    if (!el.dataset.basePrice) el.dataset.basePrice = String(baseNum);
  });

  // persist preferensi di klien
  LS.setItem('pref_currency', currency);
  LS.setItem('pref_locale',  locale);
  setCookie('pref_currency', currency, 365);
  setCookie('pref_locale',  locale,   365);

  // beri tahu skrip lain bahwa harga sudah dirender
  document.dispatchEvent(new Event('prices:rendered'));
}

// helper agar skrip lain bisa memicu render ulang
window.__pricesRefresh = async (cur, loc) => {
  const currency = (cur || LS.getItem('pref_currency') || 'IDR').toUpperCase();
  const locale   =  loc || LS.getItem('pref_locale')    || 'id-ID';
  await renderAll(currency, locale);
};

// ====== OBSERVE DOM (node/atribut baru) ======
function observe(){
  const obs = new MutationObserver((muts)=>{
    const hasNewPrice = muts.some(m => {
      if (m.type === 'attributes' && (m.attributeName === 'data-price' || m.attributeName === 'data-base-price')) {
        return true;
      }
      return Array.from(m.addedNodes||[]).some(n =>
        n.nodeType === 1 && (n.matches?.('.price,[data-price]') || n.querySelector?.('.price,[data-price]')));
    });
    if (hasNewPrice) {
      const cur = (LS.getItem('pref_currency') || 'IDR').toUpperCase();
      const loc = LS.getItem('pref_locale') || 'id-ID';
      renderAll(cur, loc);
    }
  });
  obs.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['data-price','data-base-price'] });
}

  // ====== SYNC KE BACKEND (SESSION) ======
  async function syncToServer(locale, currency) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) return;

    // Controller @update expect "locale" & "currency"
    try {
      await fetch(PREF_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ locale, currency }),
        credentials: 'same-origin',
      });
    } catch {
      // diam aja; UI tetap jalan berbasis localStorage
    }
  }

  // ====== EVENT: KLIK FAB OPTION ======
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.fab-prefs__opt');
    if (!btn) return;

    // auto location? (skip – belum implement geo)
    if (btn.dataset.auto) return;

    const locRaw = btn.dataset.locale || LS.getItem('pref_locale') || 'id-ID';
    const loc = normalizeLocale(locRaw);
    const cur = (btn.dataset.currency || DEFAULT_CURRENCY_BY_LOCALE[loc] || 'IDR').toUpperCase();

    await renderAll(cur, loc);
    syncToServer(loc, cur);
  });

  // ====== FALLBACK: <select id="langSelect"> & <select id="currencySelect"> ======
  function wireSelects() {
    const langSel = document.getElementById('langSelect');
    const curSel  = document.getElementById('currencySelect');

    langSel?.addEventListener('change', async (e) => {
      const loc = normalizeLocale(e.target.value);
      const cur = curSel?.value || DEFAULT_CURRENCY_BY_LOCALE[loc] || 'IDR';
      await renderAll(cur, loc);
      wireSelects();
      observe();
      syncToServer(loc, cur);
    });

    curSel?.addEventListener('change', async (e) => {
      const cur = (e.target.value || 'IDR').toUpperCase();
      const loc = normalizeLocale(langSel?.value || LS.getItem('pref_locale') || 'id-ID');
      await renderAll(cur, loc);
      syncToServer(loc, cur);
    });
  }

  // ====== BOOT ======
  document.addEventListener('DOMContentLoaded', async () => {
    const loc = normalizeLocale(LS.getItem('pref_locale') || document.querySelector('meta[name="app-locale"]')?.content || 'id-ID');
    const cur = (LS.getItem('pref_currency') || document.querySelector('meta[name="app-currency"]')?.content || DEFAULT_CURRENCY_BY_LOCALE[loc] || 'IDR').toUpperCase();

    await renderAll(cur, loc);
    wireSelects();
  });
})();
