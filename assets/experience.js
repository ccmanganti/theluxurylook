/* Progressive enhancement. Core content and CTAs remain available without JS. */
(()=>{
  'use strict';
  const reduced=window.matchMedia('(prefers-reduced-motion: reduce)');
  const saveData=!!navigator.connection?.saveData||/^(slow-2g|2g)$/.test(navigator.connection?.effectiveType||'');
  const reveal=[...document.querySelectorAll('.x-reveal')];
  if(!reduced.matches&&'IntersectionObserver' in window){
    const observer=new IntersectionObserver((entries,obs)=>{for(const entry of entries){if(entry.isIntersecting){entry.target.classList.add('is-visible');obs.unobserve(entry.target)}}},{rootMargin:'0px 0px -40px 0px',threshold:.04});
    reveal.forEach(el=>{el.classList.add('is-observed');observer.observe(el)});
  }
  const videos=[...document.querySelectorAll('[data-ambient-video]')];
  if(videos.length&&!reduced.matches&&!saveData&&'IntersectionObserver' in window){
    const videoObserver=new IntersectionObserver(entries=>{
      for(const entry of entries){
        const video=entry.target;
        if(entry.isIntersecting&&document.visibilityState==='visible'){
          if(!video.dataset.loaded){video.querySelectorAll('source[data-src]').forEach(src=>{src.src=src.dataset.src;delete src.dataset.src});video.load();video.dataset.loaded='1'}
          video.play().then(()=>video.closest('[data-ambient-container]')?.classList.add('is-playing')).catch(()=>{});
        }else {video.pause()}
      }
    },{rootMargin:'120px 0px',threshold:.01});videos.forEach(v=>videoObserver.observe(v));
    document.addEventListener('visibilitychange',()=>{videos.forEach(v=>document.visibilityState==='hidden'?v.pause():(v.dataset.loaded&&v.getBoundingClientRect().bottom>0&&v.getBoundingClientRect().top<innerHeight&&v.play().catch(()=>{})))});
  }
  document.querySelectorAll('[data-compare-slider]').forEach(input=>{const parent=input.closest('[data-compare]');if(parent){input.addEventListener('input',()=>parent.style.setProperty('--reveal',`${input.value}%`));}});
  const filters=[...document.querySelectorAll('.x-filter-rail [data-portfolio-filter]')];
  if(filters.length){const cards=[...document.querySelectorAll('.x-mosaic-cell[data-project-wrapper]')];const empty=document.querySelector('.empty-filter');
    const apply=value=>{let count=0;filters.forEach(b=>{let active=b.dataset.portfolioFilter===value;b.classList.toggle('is-active',active);b.setAttribute('aria-pressed',String(active))});
      cards.forEach(el=>{const card=el.querySelector('[data-category-slug]');let visible=value==='all'||card?.dataset.categorySlug===value;el.hidden=!visible;if(visible)count++});if(empty)empty.hidden=count>0;};
    filters.forEach(b=>b.addEventListener('click',()=>apply(b.dataset.portfolioFilter)));const query=new URLSearchParams(location.search).get('category');if(query&&filters.some(b=>b.dataset.portfolioFilter===query))apply(query);
  }
  document.querySelectorAll('[data-accordion]').forEach(list=>{list.querySelectorAll('details').forEach(detail=>detail.addEventListener('toggle',()=>{
    if(!detail.open)return;list.querySelectorAll('details').forEach(other=>{if(other!==detail)other.open=false});
  }))});
})();
