/* Video overlays for explicitly supplied YouTube and Vimeo links. No background video, zoom or parallax. */
(()=>{'use strict';
  const conn=navigator.connection;
  if(conn&&(conn.saveData||/^(2g|slow-2g)$/.test(conn.effectiveType||'')))document.documentElement.classList.add('x-save-data');
  const resolve=(raw)=>{
    try{
      const u=new URL(raw,location.href);
      if(u.protocol!=='https:'||u.username||u.password||u.port)return null;
      const host=u.hostname.toLowerCase().replace(/^www\./,'');
      let id=null;
      if(host==='youtu.be')id=u.pathname.split('/')[1];
      else if(['youtube.com','m.youtube.com','youtube-nocookie.com'].includes(host))id=u.searchParams.get('v')||u.pathname.match(/^\/(?:embed|shorts|live)\/([\w-]{11})\/?$/)?.[1];
      if(/^[\w-]{11}$/.test(id||''))return {provider:'YouTube',src:`https://www.youtube-nocookie.com/embed/${id}?autoplay=1&playsinline=1&rel=0`,original:`https://www.youtube.com/watch?v=${id}`};
      if(!['vimeo.com','player.vimeo.com'].includes(host))return null;
      const parts=u.pathname.split('/').filter(Boolean);
      const numeric=host==='player.vimeo.com'?(parts[0]==='video'&&parts.length===2?parts[1]:null):parts[0];
      if(!numeric||!/^\d+$/.test(numeric)||parts.length>(host==='player.vimeo.com'?2:2))return null;
      const hash=u.searchParams.get('h')||(host==='vimeo.com'?parts[1]:null);
      if(hash&&!/^[a-f0-9]{6,32}$/i.test(hash))return null;
      const params=new URLSearchParams({autoplay:'1',title:'0',byline:'0',portrait:'0'});
      if(hash)params.set('h',hash);
      return {provider:'Vimeo',src:`https://player.vimeo.com/video/${numeric}?${params.toString()}`,original:`https://vimeo.com/${numeric}${hash?'/'+hash:''}`};
    }catch{return null}
  };
  let overlay=null,opener=null;
  function close(){if(!overlay)return;overlay.remove();overlay=null;document.body.style.overflow='';opener?.focus();opener=null;}
  function open(raw,button){const video=resolve(raw);if(!video)return;close();opener=button;
    overlay=document.createElement('div');overlay.className='x-video-overlay';overlay.setAttribute('role','dialog');overlay.setAttribute('aria-modal','true');overlay.setAttribute('aria-label',`${video.provider} video player`);
    const panel=document.createElement('div');panel.className='x-video-overlay__panel';
    const dismiss=document.createElement('button');dismiss.className='x-video-overlay__close';dismiss.type='button';dismiss.textContent='Close film ✕';dismiss.addEventListener('click',close);
    const player=document.createElement('div');player.className='x-video-overlay__player';
    const frame=document.createElement('iframe');frame.title=button.dataset.videoTitle||'The Luxury Look film';frame.allow='autoplay; fullscreen; picture-in-picture; encrypted-media';frame.allowFullscreen=true;frame.referrerPolicy='strict-origin-when-cross-origin';frame.loading='eager';frame.src=video.src;
    const source=document.createElement('a');source.className='x-video-overlay__source';source.href=video.original;source.target='_blank';source.rel='noopener noreferrer';source.textContent=`Watch on ${video.provider} if the player is unavailable`;
    player.append(frame);panel.append(dismiss,player,source);overlay.append(panel);
    overlay.addEventListener('click',e=>{if(e.target===overlay)close()});document.body.append(overlay);document.body.style.overflow='hidden';dismiss.focus();
  }
  document.addEventListener('click',e=>{const button=e.target.closest('[data-video-url],[data-youtube-url]');if(!button)return;e.preventDefault();open(button.dataset.videoUrl||button.dataset.youtubeUrl,button)});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&overlay)close()});
})();
