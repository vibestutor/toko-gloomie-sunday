/**
 * JS HALAMAN DETAIL PRODUK — FINAL (sinkron dgn show.blade kamu)
 * - Pilih warna & ukuran → state tersimpan (tidak saling reset)
 * - Qty +/-
 * - Add to Cart → POST /add-to-cart → redirect /cart
 * - Buy Now → POST /buy-now → redirect /checkout
 * - Gak perlu ubah Blade lagi
 */
(function () {
  'use strict';

  const container = document.querySelector('.product-detail-container');
  if (!container) return;

  // guard supaya tidak double bid di tombol qty +/-

  if (container.dataset.inited === '1') return;
  container.dataset.inited = '1';

  // Helpers
  const toInt = (v, fb=0) => (isNaN(parseInt(v,10)) ? fb : parseInt(v,10));
  const formatRupiah = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(toInt(num,0));

  let selectedColor = null;
  let selectedSize  = null;

  // Qty
  const minusBtn = container.querySelector('.minus-btn');
  const plusBtn  = container.querySelector('.plus-btn');
  const quantityInput = container.querySelector('.quantity-input input');

  if (plusBtn && quantityInput) {
    plusBtn.addEventListener('click', () => quantityInput.value = toInt(quantityInput.value,1)+1);
  }
  if (minusBtn && quantityInput) {
    minusBtn.addEventListener('click', () => {
      const n = toInt(quantityInput.value,1);
      quantityInput.value = n>1 ? n-1 : 1;
    });
  }

  // SIZE
  const sizeOptions = container.querySelectorAll('.pd-size-option');
  if (sizeOptions.length){
    sizeOptions.forEach(op=>{
      op.addEventListener('click', ()=>{
        sizeOptions.forEach(b=>b.classList.remove('selected'));
        op.classList.add('selected');
        selectedSize = op.textContent.trim();
      });
    });
  }

  // COLOR
  const colorButtons = container.querySelectorAll('.pd-color-option');
  const mainImg = document.getElementById('product-image');
  const priceEl = document.getElementById('product-price');

  const setActiveColor = (btn)=>{
    colorButtons.forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    selectedColor = btn.getAttribute('data-color');
  };

  if (colorButtons.length){
    colorButtons.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const img   = btn.getAttribute('data-image');
        const price = btn.getAttribute('data-price');
        if (img && mainImg) mainImg.src = img;
        if (price && priceEl) priceEl.textContent = formatRupiah(price);
        setActiveColor(btn);
      });
    });
    // auto pilih pertama
    colorButtons[0].click();
  }

  // Thumbnails (kalau ada)
  const thumbs = container.querySelectorAll('#product-thumbnails .pd-thumbnail');
  if (thumbs.length){
    const setActive = (el,list)=>{ list.forEach(t=>t.classList.remove('active')); el.classList.add('active'); };
    thumbs.forEach(t=>{
      t.addEventListener('click', ()=>{
        if (mainImg) mainImg.src = t.src;
        setActive(t, thumbs);
      });
    });
    thumbs[0].classList.add('active');
  }

  // Kirim ke server via fetch
  async function send(actionPath){
    const productId = container.getAttribute('data-product-id');
    const qty = toInt(quantityInput?.value ?? 1, 1);

    if (!selectedSize){ alert('Pilih ukuran terlebih dahulu.'); return; }
    if (!selectedColor){ alert('Pilih warna terlebih dahulu.'); return; }

    try{
      const res = await fetch(actionPath, {
        method: 'POST',
        headers: {
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          product_id: productId,
          color: selectedColor,
          size: selectedSize,
          qty
        })
      });
      if (!res.ok) throw new Error('Request gagal');

      // redirect sesuai action
      if (actionPath === '/add-to-cart') window.location.href = '/cart';
      if (actionPath === '/buy-now')     window.location.href = '/checkout';
    }catch(e){
      console.error(e);
      alert('Terjadi kesalahan. Coba lagi.');
    }
  }

  const addToCartBtn = container.querySelector('.btn-add-to-cart');
  const buyNowBtn    = container.querySelector('.btn-buy-now');

  addToCartBtn?.addEventListener('click', ()=> send('/add-to-cart'));
  buyNowBtn?.addEventListener('click', ()=> send('/buy-now'));

})();
