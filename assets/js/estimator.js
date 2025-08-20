(function(){
  function calcMix(target, sizes){
    const mix={large:0,medium:0,small:0};
    const order=['large','medium','small']; let remaining=target;
    for(const k of order){
      const n=Math.floor(remaining/sizes[k]); if(n>0){ mix[k]=n; remaining-=n*sizes[k]; }
    }
    if(remaining>0){ mix.small+=1; } return mix;
  }
  function toText(mix){ const p=[]; if(mix.large)p.push(mix.large+' Large'); if(mix.medium)p.push(mix.medium+' Medium'); if(mix.small)p.push(mix.small+' Small'); return p.length?p.join(' + '):'1 Small'; }
  function servings(mix,s){ return mix.large*s.large + mix.medium*s.medium + mix.small*s.small; }

  document.addEventListener('click', function(e){
    if(e.target && e.target.id==='ge-calc'){
      const wrap=e.target.closest('.annesfs-ge');
      const portions=JSON.parse(wrap.getAttribute('data-servings'));
      const s={ small: portions.small[1], medium: portions.medium[1], large: portions.large[1] };
      const modes=JSON.parse(wrap.getAttribute('data-modes'));
      const buffer=parseFloat(wrap.getAttribute('data-buffer'))||0.10;
      const guests=Math.max(1, parseInt(document.getElementById('ge-guests').value||'0',10));
      const mode=document.getElementById('ge-mode').value;
      const perGuest=modes[mode]||1.7;
      const base=guests*perGuest;
      const target=Math.ceil(base*(1+buffer));
      const mix=calcMix(target,s);
      const total=servings(mix,s);
      const summary=`For ${guests} guests (${mode==='hearty'?'Hearty':'Standard/Light'}): ${toText(mix)} (≈ ${total} servings incl. buffer)`;
      const box=document.getElementById('ge-result'); box.style.display='block';
      box.querySelector('.annesfs-ge__summary').textContent=summary; box.dataset.plan=summary + '. Rice not included.';
    }
    if(e.target && e.target.id==='ge-copy'){
      const box=document.getElementById('ge-result'); const txt=box?.dataset?.plan||'Catering plan';
      navigator.clipboard.writeText(txt).then(()=>{ e.target.textContent='Copied!'; setTimeout(()=>e.target.textContent='Copy plan',1000); });
    }
  });
})();