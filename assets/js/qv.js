(function($){
  function openQV(pid){
    var $wrap = $('#annesfs-qv');
    $wrap.show().addClass('is-open');
    $wrap.find('.annesfs-qv__content').html('<div class="annesfs-qv__loading">Loading…</div>');
    $.get((window.ANNESFS_AJAX||{}).url, { action:'annesfs_qv', pid: pid })
      .done(function(r){
        if(r && r.success){ 
          $wrap.find('.annesfs-qv__content').html(r.data.html);
          enhancePriceButton($wrap);
        } else { 
          $wrap.find('.annesfs-qv__content').html('<p>Could not load.</p>'); 
        }
      })
      .fail(function(){ $wrap.find('.annesfs-qv__content').html('<p>Network error.</p>'); });
  }
  function closeQV(){ $('#annesfs-qv').removeClass('is-open').hide(); }
  $('body').on('click', '.annesfs-qv-btn', function(e){ e.preventDefault(); var pid=$(this).data('pid'); if(pid) openQV(pid); });
  $('body').on('click','.annesfs-qv__close, .annesfs-qv__backdrop', function(){ closeQV(); });

  // Submit via AJAX
  $('body').on('submit', '.qv-form form.cart', function(e){
    e.preventDefault();
    var $form=$(this);
    var data=$form.serializeArray();
    var url=(window.wc_add_to_cart_params&&window.wc_add_to_cart_params.wc_ajax_url)?
      window.wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%','add_to_cart'):((window.ANNESFS_AJAX||{}).url);
    $.post(url,data)
      .done(function(res){
        $(document.body).trigger('added_to_cart',[res.fragments,res.cart_hash,$form]);
        $(document.body).trigger('wc_fragment_refresh');
        closeQV();
      })
      .fail(function(){ alert('Could not add to cart.'); });
  });

  // ---- Price-aware button text
  function enhancePriceButton($wrap){
    var $form = $wrap.find('.qv-form form.cart');
    if (!$form.length) return;
    var $btn  = $form.find('button[type=submit], .single_add_to_cart_button').first();
    if (!$btn.length) return;

    function readPrice(){
      var $p = $wrap.find('.woocommerce-variation-price .price, .qv-price .price').first();
      if(!$p.length) return null;
      var txt = $p.text().replace(/[^0-9.]/g,'');
      var val = parseFloat(txt||'0');
      return isNaN(val) ? null : val;
    }
    function readQty(){
      var q = parseInt($form.find('input.qty').val()||'1',10);
      return isNaN(q)||q<1 ? 1 : q;
    }
    function update(){
      var price = readPrice();
      var qty   = readQty();
      if(price){
        var total = (price * qty).toFixed(2);
        var base  = $btn.data('base') || $btn.text();
        if(!$btn.data('base')) $btn.data('base', base);
        $btn.text(base.replace(/\s*\$[0-9,.]+$/, '') + '  ·  $' + total);
      }
    }
    $form.on('change', 'select', update);
    $form.on('input', 'input.qty', update);
    const obs = new MutationObserver(update);
    $wrap.find('.woocommerce-variation-price').each(function(){ obs.observe(this,{childList:true,subtree:true,characterData:true}); });
    setTimeout(update, 100);
  }
})(jQuery);
