/* Small, accessible interactions shared by the Laravel pages and static review site. */
(()=>{
  const menu=document.getElementById('primary-nav'),toggle=document.querySelector('.nav-toggle');
  if(menu&&toggle){
    const setOpen=open=>{menu.classList.toggle('is-open',open);toggle.setAttribute('aria-expanded',String(open));toggle.setAttribute('aria-label',open?'Close navigation':'Open navigation')};
    toggle.addEventListener('click',()=>setOpen(!menu.classList.contains('is-open')));
    menu.addEventListener('click',ev=>{if(ev.target.closest('a'))setOpen(false)});
    document.addEventListener('keydown',ev=>{if(ev.key==='Escape')setOpen(false)});
  }
  const filters=[...document.querySelectorAll('[data-portfolio-filter]')];
  const cards=[...document.querySelectorAll('[data-category-slug]')];
  if(filters.length){
    const update=value=>{
      let visible=0;
      filters.forEach(b=>{const selected=b.dataset.portfolioFilter===value;b.classList.toggle('is-active',selected);b.setAttribute('aria-pressed',String(selected))});
      cards.forEach(card=>{const show=value==='all'||card.dataset.categorySlug===value;card.hidden=!show;if(show)visible++});
      const empty=document.querySelector('.empty-filter');if(empty)empty.hidden=visible!==0;
    };
    filters.forEach(b=>b.addEventListener('click',()=>update(b.dataset.portfolioFilter)));
    const q=new URLSearchParams(location.search).get('category');
    if(q&&filters.some(b=>b.dataset.portfolioFilter===q))update(q);
  }
  document.querySelectorAll('.media-frame img').forEach(img=>img.addEventListener('error',()=>img.closest('.media-frame')?.classList.add('is-missing'),{once:true}));
  const box=document.querySelector('.lightbox');
  if(box){
    const buttons=[...document.querySelectorAll('[data-gallery-image]')];
    const image=box.querySelector('.lightbox-image'),count=box.querySelector('.lightbox-count');
    let current=0,returnFocus=null;
    const show=index=>{if(!buttons.length)return;current=(index+buttons.length)%buttons.length;image.src=buttons[current].dataset.galleryImage;image.alt=buttons[current].dataset.galleryAlt||'Project gallery photograph';if(count)count.textContent=`${current+1} / ${buttons.length}`};
    const close=()=>{box.hidden=true;box.setAttribute('aria-hidden','true');document.body.style.overflow='';if(returnFocus)returnFocus.focus()};
    buttons.forEach((button,i)=>button.addEventListener('click',()=>{returnFocus=button;show(i);box.hidden=false;box.setAttribute('aria-hidden','false');document.body.style.overflow='hidden';box.querySelector('.lightbox-close')?.focus()}));
    box.querySelector('.lightbox-close')?.addEventListener('click',close);
    box.querySelector('.lightbox-prev')?.addEventListener('click',()=>show(current-1));
    box.querySelector('.lightbox-next')?.addEventListener('click',()=>show(current+1));
    box.addEventListener('click',ev=>{if(ev.target===box)close()});
    document.addEventListener('keydown',ev=>{if(box.hidden)return;if(ev.key==='Escape')close();if(ev.key==='ArrowRight')show(current+1);if(ev.key==='ArrowLeft')show(current-1)});
  }
})();
