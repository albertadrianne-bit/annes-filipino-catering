(function($){
  function openQV(pid){
    var $wrap=$('#annesfs-qv'); 
    if(!$wrap.length){
      $('body').append($('#annesfs-qv')); 
      $wrap=$('#annesfs-qv');
    }
    $wrap.show().addClass('is-open');
    $wrap.find('.annesfs-qv__content').html('<div class="annesfs-qv__loading">Loading…</div>');
    $.get((window.ANNESFS_BUNDLES||{}).ajax,{action:'annesfs_qv',pid:pid}).done(function(r){
      if(r&&r.success){ $wrap.find('.annesfs-qv__content').html(r.data.html); }
      else{ $wrap.find('.annesfs-qv__content').html('<p>Could not load.</p>'); }
    }).fail(function(){ $wrap.find('.annesfs-qv__content').html('<p>Network error.</p>'); });
  }
  function closeQV(){ $('#annesfs-qv').removeClass('is-open').hide(); }
  $('body').on('click','.annesfs-qv-btn',function(e){ e.preventDefault(); openQV($(this).data('pid')); });
  $('body').on('click','.annesfs-qv__close,.annesfs-qv__backdrop',function(){ closeQV(); });
})(jQuery);
