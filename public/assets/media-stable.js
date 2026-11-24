/* Stable, privacy-conscious YouTube click-to-play overlays. No zoom, parallax, or autoplay. */
(()=>{'use strict';
  const conn=navigator.connection;
  if(conn&&(conn.saveData||/^(2g|slow-2g)$/.test(conn.effectiveType||'')))document.documentElement.classList.add('x-save-data');
  const decodeId=(input)=>{
    try {const u=new URL(input,location.href);const h=u.hostname.toLowerCase().replace(/^www\./,'');
      let id=null;
      if(h==='youtu.be')id=u.pathname.split('/')[1];
      else if(['youtube.com','m.youtube.com','youtube-nocookie.com'].includes(h))id=u.searchParams.get('v')||u.pathname.match(/^\/(?:embed|shorts|live)\/([\w-]{11})/)?.[1];
      return /^[\w-]{11}$/.test(id||'')?id:null;
    } catch{return null;}
  };
  let overlay=null, opener=null;
  function close(){if(!overlay)return;overlay.remove();overlay=null;document.body.style.overflow='';opener?.focus();opener=null;}
  function open(url,button){const id=decodeId(url);if(!id)return;close();opener=button;
    overlay=document.createElement('div');overlay.className='x-video-overlay';overlay.setAttribute('role','dialog');overlay.setAttribute('aria-modal','true');overlay.setAttribute('aria-label','Studio video');
    const panel=document.createElement('div');panel.className='x-video-overlay__panel';
    const dismiss=document.createElement('button');dismiss.className='x-video-overlay__close';dismiss.type='button';dismiss.textContent='Close video ✕';dismiss.addEventListener('click',close);
    const player=document.createElement('div');player.className='x-video-overlay__player';
    const frame=document.createElement('iframe');frame.title=button.dataset.videoTitle||'The Luxury Look studio video';frame.allow='autoplay; encrypted-media; picture-in-picture; fullscreen';frame.allowFullscreen=true;frame.referrerPolicy='strict-origin-when-cross-origin';
    frame.src=`https://www.youtube-nocookie.com/embed/${id}?autoplay=1&playsinline=1&rel=0`;
    player.append(frame);panel.append(dismiss,player);overlay.append(panel);overlay.addEventListener('click',e=>{if(e.target===overlay)close();});document.body.append(overlay);document.body.style.overflow='hidden';dismiss.focus();
  }
  document.addEventListener('click',e=>{const button=e.target.closest('[data-youtube-url]');if(!button)return;e.preventDefault();open(button.dataset.youtubeUrl,button);});
  document.addEventListener('keydown',e=>{if(e.key==='Escape'&&overlay)close();});
})();
