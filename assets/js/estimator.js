(function(){
  function calcMix(target, sizes){
    const mix={large:0,medium:0,small:0};
    const order=['large','medium','small'];
    let remaining=target;
    for(const k of order){
      const n=Math.floor(remaining/sizes[k]);
      if(n>0){mix[k]=n;remaining-=n*sizes[k];}
    }
    if(remaining>0){mix.small+=1;}
    return mix;
  }
  function toText(mix){
    const p=[];
    if(mix.large)p.push(mix.large+' Large');
    if(mix.medium)p.push(mix.medium+' Medium');
    if(mix.small)p.push(mix.small+' Small');
    return p.length?p.join(' + '):'1 Small';
  }
  document.addEventListener('click',function(e){
    if(e.target&&e.target.id==='ge-calc'){
      const wrap=e.target.closest('.annesfs-ge')||document;
      const portions=JSON.parse(wrap.getAttribute('data-servings'));
      const s={small:portions.small[0],medium:portions.medium[0],large:portions.large[0]};
      const modes=JSON.parse(wrap.getAttribute('data-modes'));
      const riceAdj=parseFloat(wrap.getAttribute('data-riceadj'))||0.12;

      const guests=Math.max(1,parseInt(document.getElementById('ge-guests').value||'0',10));
      const mode=document.getElementById('ge-mode').value||'hearty';
      let perGuest=modes[mode]||1.4;
      if(document.getElementById('ge-rice')?.checked){ perGuest=Math.max(1.0, perGuest*(1.0-riceAdj)); }
      const target=Math.ceil(guests*perGuest);

      const mix=calcMix(target,s);
      const total = mix.large*s.large + mix.medium*s.medium + mix.small*s.small;

      const summary=`For ${guests} guests (${mode}, ${document.getElementById('ge-rice')?.checked?'with rice/noodles':'no rice'}): ${toText(mix)} (feeds ~${total}).`;
      const box=document.getElementById('ge-result');box.style.display='block';
      const sum=box.querySelector('.annesfs-ge__summary'); if(sum) sum.textContent=summary;

      const bundle=box.querySelector('.annesfs-ge__bundle');
      let suggestion='';
      if(total>=84){suggestion='Holiday Feast Bundle (Feeds ~84)';}
      else if(total>=60){suggestion='Mega Handaan (Feeds ~60–75)';}
      else if(total>=40){suggestion='Salo-Salo (Feeds ~40–50)';}
      else if(total>=10){suggestion='Sakto Lang (Feeds ~10–20)';}
      if(bundle) bundle.textContent = suggestion ? ('Recommended: '+suggestion) : '';
    }
  });
})();
