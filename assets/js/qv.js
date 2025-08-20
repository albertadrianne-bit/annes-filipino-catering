(function($){
  function openQV(pid){
    var $wrap = $('#annesfs-qv');
    $wrap.show().addClass('is-open');
    $wrap.find('.annesfs-qv__content').html('<div class="annesfs-qv__loading">Loading…</div>');
    $.get((window.ANNESFS_AJAX||{}).url, { action:'annesfs_qv', pid: pid })
      .done(function(r){
        if(r && r.success){ $wrap.find('.annesfs-qv__content').html(r.data.html); }
        else { $wrap.find('.annesfs-qv__content').html('<p>Could not load.</p>'); }
      })
      .fail(function(){ $wrap.find('.annesfs-qv__content').html('<p>Network error.</p>'); });
  }
  function closeQV(){ $('#annesfs-qv').removeClass('is-open').hide(); }
  $('body').on('click', '.annesfs-qv-btn', function(e){ e.preventDefault(); var pid=$(this).data('pid'); if(pid) openQV(pid); });
  $('body').on('click','.annesfs-qv__close, .annesfs-qv__backdrop', function(){ closeQV(); });
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
})(jQuery);