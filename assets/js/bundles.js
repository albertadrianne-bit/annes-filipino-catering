(function($){
  function openBundle(bid){
    var $wrap=$('#annesfs-bundle');
    if(!$wrap.length){
      $('body').append('<div id="annesfs-bundle" class="annesfs-bundle-modal"><div class="annesfs-bundle-backdrop"></div><div class="annesfs-bundle-panel"><button class="annesfs-bundle-close">×</button><div class="annesfs-bundle-content"></div></div></div>');
      $wrap=$('#annesfs-bundle');
    }
    $wrap.addClass('is-open');
    $wrap.find('.annesfs-bundle-content').html('<div class="annesfs-bundle-loading">Loading bundle…</div>');
    $.get((window.ANNESFS_BUNDLES||{}).ajax,{action:'annesfs_bundle_modal',bid:bid}).done(function(r){
      if(r&&r.success){ $wrap.find('.annesfs-bundle-content').html(r.data.html); }
      else{ $wrap.find('.annesfs-bundle-content').html('<p>Could not load bundle.</p>'); }
    }).fail(function(){ $wrap.find('.annesfs-bundle-content').html('<p>Network error.</p>'); });
  }
  function closeBundle(){ $('#annesfs-bundle').removeClass('is-open'); }
  $('body').on('click','.annesfs-bundle-build',function(e){ e.preventDefault(); openBundle($(this).data('bundle')); });
  $('body').on('click','.annesfs-bundle-close,.annesfs-bundle-backdrop',function(){ closeBundle(); });
})(jQuery);
