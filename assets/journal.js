/* Editorial interactions: search/filter, in-page table of contents, progress. No zoom or scroll parallax. */
(()=>{
  'use strict';
  const posts=[...document.querySelectorAll('[data-journal-entry]')];
  const search=document.querySelector('[data-journal-search-input]');
  const cats=[...document.querySelectorAll('[data-journal-category]')];
  const empty=document.querySelector('[data-journal-empty]');
  let category='All';
  const filter=()=>{
    const term=(search?.value||'').toLocaleLowerCase().trim();
    let shown=0;
    posts.forEach(p=>{
      const allowed=category==='All'||p.dataset.journalCategoryValue===category;
      const matches=!term||(p.dataset.journalSearch||'').toLocaleLowerCase().includes(term);
      p.hidden=!(allowed&&matches);if(!p.hidden)shown++;
    });
    if(empty)empty.hidden=shown>0;
  };
  search?.addEventListener('input',filter);
  cats.forEach(btn=>btn.addEventListener('click',()=>{
    category=btn.dataset.journalCategory;
    cats.forEach(b=>{const yes=b===btn;b.classList.toggle('is-active',yes);b.setAttribute('aria-pressed',String(yes))});
    filter();
  }));
  const article=document.querySelector('[data-article-body]'),toc=document.querySelector('[data-article-toc]');
  if(article&&toc){
    const hs=[...article.querySelectorAll('h2,h3')];
    const slugify=(s)=>s.toLocaleLowerCase().replace(/[^\p{L}\p{N}]+/gu,'-').replace(/^-|-$/g,'');
    hs.forEach((h,i)=>{
      const id='section-'+i+'-'+slugify(h.textContent).slice(0,40);h.id=id;
      const link=document.createElement('a');link.href='#'+id;link.textContent=h.textContent;
      if(h.tagName==='H3')link.style.paddingLeft='12px';
      toc.append(link);
    });
  }
  const progress=document.querySelector('[data-reading-progress]');
  if(article&&progress){
    let ticking=false;
    const update=()=>{
      const r=article.getBoundingClientRect();
      const total=r.height+innerHeight;
      const done=Math.max(0,Math.min(1,(innerHeight-r.top)/total));
      progress.style.width=(done*100).toFixed(1)+'%';ticking=false;
    };
    addEventListener('scroll',()=>{if(!ticking){ticking=true;requestAnimationFrame(update)}},{passive:true});
    update();
  }
  document.querySelector('[data-copy-article-link]')?.addEventListener('click',async e=>{
    const b=e.currentTarget;
    try{await navigator.clipboard.writeText(location.href);b.textContent='Link copied ✓'}
    catch{b.textContent='Copy your browser address to share'}
  });
})();
