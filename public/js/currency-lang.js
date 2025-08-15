(function(){
  const LS = window.localStorage;
  const RATE_TTL_MS = 30*60*1000;
  const API_BASE = 'https://api.exchangerate.host';

  const LOCALE_DEFAULT_CURRENCY = { 'id-ID':'IDR', 'en-US':'USD', 'en-GB':'GBP', 'en-AU':'AUD', 'en-SG':'SGD', 'en-CA':'CAD', 'ja-JP':'JPY', 'de-DE':'EUR', 'fr-FR':'EUR' };

  const parsePriceText = txt => { const digits = String(txt||'').replace(/[^0-9]/g,''); return digits?Number(digits):0; };
  const formatCurrency = (num, currency, locale)=>{ try{ return new Intl.NumberFormat(locale||undefined,{style:'currency',currency:currency||'IDR',minimumFractionDigits:0}).format(Number(num||0)); } catch(e){ return (currency?'${currency} ':'') + (Number(num||0).toLocaleString(locale||undefined)); }};
  const now = ()=>new Date().getTime();

  const getCache = key=>{try{return JSON.parse(LS.getItem(key));}catch{return null;}};
  const setCache = (key,val)=>LS.setItem(key,JSON.stringify(val));

  const memRate = {};
  async function getRate(from,to){
    if(from===to) return 1;
    const memKey=`${from}_${to}`;
    if(memRate[memKey]) return memRate[memKey];
    const lsKey=`rate_${memKey}`;
    const cached=getCache(lsKey);
    if(cached&&(now()-cached.t)<RATE_TTL_MS){ memRate[memKey]=cached.v; return cached.v; }
    try{
      const r=await fetch(`${API_BASE}/latest?base=${encodeURIComponent(from)}&symbols=${encodeURIComponent(to)}`);
      const j=await r.json();
      const v=(j&&j.rates&&j.rates[to])?j.rates[to]:1;
      memRate[memKey]=v;
      setCache(lsKey,{v,t:now()});
      return v;
    }catch(err){console.warn('Rate fetch failed:',err); return 1;}
  }

  async function populateCurrencies(select){
    if(!select) return;
    try{
      const r=await fetch(`${API_BASE}/symbols`);
      const j=await r.json();
      const symbols=j.symbols||{};
      const common=['IDR','USD','EUR','JPY','SGD','AUD','GBP','MYR'];
      const frag=document.createDocumentFragment();
      const addOpt=(code,desc)=>{const o=document.createElement('option'); o.value=code;o.textContent=`${desc} (${code})`;frag.appendChild(o);};
      common.forEach(c=>{if(symbols[c])addOpt(c,symbols[c].description);});
      Object.keys(symbols).filter(c=>!common.includes(c)).sort().forEach(c=>addOpt(c,symbols[c].description));
      select.innerHTML=''; select.appendChild(frag);
    }catch(err){
      console.warn('Currency symbols fetch failed:',err);
      select.innerHTML=`<option value="IDR">Indonesian Rupiah (IDR)</option><option value="USD">US Dollar (USD)</option><option value="EUR">Euro (EUR)</option><option value="JPY">Japanese Yen (JPY)</option>`;
    }
  }

  function initialPrefs(){
    const savedLocale=LS.getItem('pref_locale');
    const savedCurrency=LS.getItem('pref_currency');
    if(savedLocale && savedCurrency) return {locale:savedLocale,currency:savedCurrency};
    const navLoc=navigator.language||'id-ID';
    const locale=(['id','in'].includes(navLoc.split('-')[0])?'id-ID':navLoc);
    const currency=LOCALE_DEFAULT_CURRENCY[locale]||'IDR';
    return {locale,currency};
  }

  function setCookie(name,value,days){
    const d=new Date(); d.setTime(d.getTime()+(days*24*60*60*1000));
    document.cookie=`${name}=${encodeURIComponent(value)}; expires=${d.toUTCString()}; path=/`;
  }

  async function renderAll(currency,locale){
    const nodes=document.querySelectorAll('.price,[data-price]');
    const rate=await getRate('IDR',currency);
    nodes.forEach(el=>{
      if(!el.dataset.basePrice){
        const base=el.dataset.price?Number(el.dataset.price):parsePriceText(el.textContent);
        el.dataset.basePrice=String(base||0);
        if(!el.dataset.price) el.dataset.price=el.dataset.basePrice;
      }
      const baseIDR=Number(el.dataset.basePrice||0);
      el.textContent=formatCurrency(baseIDR*rate,currency,locale);
    });
    LS.setItem('pref_currency',currency);
    LS.setItem('pref_locale',locale);
    setCookie('pref_currency',currency,365);
    setCookie('pref_locale',locale,365);
    const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if(csrf) fetch('/prefs',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({currency,locale})}).catch(()=>{});
  }

  function observe(){
    const obs=new MutationObserver(muts=>{
      const hasNewPrice=muts.some(m=>Array.from(m.addedNodes||[]).some(n=>n.nodeType===1 && (n.matches?.('.price,[data-price]')||n.querySelector?.('.price,[data-price]'))));
      if(hasNewPrice){
        const currency=LS.getItem('pref_currency')||'IDR';
        const locale=LS.getItem('pref_locale')||'id-ID';
        renderAll(currency,locale);
      }
    });
    obs.observe(document.body,{childList:true,subtree:true});
  }

  async function boot(){
    const {locale,currency}=initialPrefs();
    const langSel=document.getElementById('langSelect');
    const curSel=document.getElementById('currencySelect');
    if(curSel) await populateCurrencies(curSel);
    if(langSel) langSel.value=locale;
    if(curSel) curSel.value=currency;
    await renderAll(currency,locale);
    observe();
    langSel?.addEventListener('change',async e=>{
      const newLocale=e.target.value;
      const currentCurrency=curSel?.value||LOCALE_DEFAULT_CURRENCY[newLocale]||'IDR';
      await renderAll(currentCurrency,newLocale);
    });
    curSel?.addEventListener('change',async e=>{
      const newCurrency=e.target.value;
      const currentLocale=langSel?.value||(LS.getItem('pref_locale')||'id-ID');
      await renderAll(newCurrency,currentLocale);
    });
  }

  document.addEventListener('DOMContentLoaded',boot);
})();
