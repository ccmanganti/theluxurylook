(()=>{const service=document.getElementById('inquiry-service'); if(!service)return;
const update=()=>{const value=(service.value||'').toLowerCase();const staging=/staging|listing/.test(value),builder=/builder|developer/.test(value);document.querySelectorAll('[data-inquiry-branch]').forEach(node=>{const on=node.dataset.inquiryBranch==='staging'?staging:builder;node.hidden=!on;node.querySelectorAll('input,select,textarea').forEach(el=>{el.disabled=!on})})};
service.addEventListener('change',update);update();})();
