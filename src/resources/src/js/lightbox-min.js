import{focusFirstDescendant,focusableElements}from"./utils.js";const initLightbox=({cssClasses:e,identifier:t,launchLightboxCssClass:s,responsive:n,srcsetSizes:o,translations:i})=>{const r=document.querySelector("body"),l=document.getElementById(`${t}-modal`),a=document.getElementById(`${t}-container`),c=document.getElementById(`${t}-frame`),d=document.getElementById(`${t}-control-previous`),u=document.getElementById(`${t}-control-next`),m=document.getElementById(`${t}-control-close`),y=document.getElementById(`${t}-info-caption`),g=document.getElementById(`${t}-info-total`),h=document.querySelectorAll(`.${s}`),p={},b={timing:500,currentGallery:"default",current:-1,open:!1,touch:{startPos:0,lastPos:0,direction:0,moveThreshold:50},lastKeyboardControl:!1,isTouchDevice:"ontouchstart"in window,supportedResponsiveMimeTypes:["image/jpeg","image/png","image/tiff","image/webp"]},f=()=>{window.initialFocus&&window.initialFocus.focus(),l.style.display="none",l.setAttribute("aria-label",i.LABEL),l.setAttribute("aria-hidden",!0),r.classList.remove("disablescroll"),b.current=-1,b.open=!1,g.style.display="none",g.innerText="",y.style.display="none",c.innerHTML="",c.classList.remove(`${e.lightboxContent}--loading`),b.lastKeyboardControl=!1},v=()=>{b.touch.direction=-1,A(b.current-(E()?1:0))},L=()=>{b.touch.direction=1,A(b.current+(w()?1:0))},E=()=>b.current>0,w=()=>b.current<p[b.currentGallery].images.length-1,I=()=>{d.style.setProperty("display","none"),u.style.setProperty("display","none")},P=()=>{d.style.removeProperty("display"),u.style.removeProperty("display")},A=t=>{const s=p[b.currentGallery].images.length;t>=0&&t<=s-1&&s>0&&(b.open||(()=>{const e=p[b.currentGallery].images.length,t=p[b.currentGallery].title;let s=1==e?i.UNTITLED_DYNAMIC_LABEL_s:i.UNTITLED_DYNAMIC_LABEL_p;"untitled"!==t&&(s=1==e?i.DYNAMIC_LABEL_s:i.DYNAMIC_LABEL_p),s=s.replace("{total}",e),s=s.replace("{title}",t),l.setAttribute("aria-label",s),l.style.display="flex",l.setAttribute("aria-hidden",!1),r.classList.add("disablescroll"),b.open=!0,a.focus(),focusFirstDescendant(a,m)})(),b.current!==t&&(b.current=t,E()||w()?(P(),E()?(d.classList.remove("visibility-hidden"),d.disabled=!1):(d.classList.add("visibility-hidden"),d.disabled=!0),w()?(u.classList.remove("visibility-hidden"),u.disabled=!1):(u.classList.add("visibility-hidden"),u.disabled=!0)):I(),"right"===b.lastKeyboardControl&&(u.disabled?l.focus():u.focus()),"left"===b.lastKeyboardControl&&(d.disabled?l.focus():d.focus()),x(),window.setTimeout((()=>{C();const s=(t=>{const{mimetype:s,srcsetImages:r,title:l,url:a}=p[b.currentGallery].images[t];if(b.supportedResponsiveMimeTypes.includes(s)&&n)return`\n        <picture class="${e.lightboxPicture}">\n          <source\n            type="${s}"\n            srcset="${o.map(((e,t)=>`${r[t]} ${e}w,`))}\n            ${a}" />\n          <img\n            alt=""\n            class="${e.lightboxImage}"\n            loading="lazy"\n            src="${r[0]}" />\n        </picture>\n      `;if(s.indexOf("image")<0){const e=s.substring(s.lastIndexOf("/")+1);return`<p style="text-align: center;">${i.UNSUPPORTED_FILETYPE.replace("{ext}",e)}<br/><a href="${a}" target="_blank">${l}</a><br/>${a.substring(a.lastIndexOf("/")+1)}</p>`}return`\n      <img\n        alt=""\n        class="${e.lightboxImage}"\n        loading="lazy"\n        src="${a}" />\n    `})(t),{averageColor:r}=p[b.currentGallery].images[t];if(c.innerHTML=s,r){const e=r.replace(/[^\d,]/g,"").split(",");l.style.backgroundColor=`hsla(${e[0]},${e[1]}%,${.5*e[2]}%,0.95)`}const a=c.querySelector("img");a&&(c.classList.add(e.lightboxImageLoading),a.onload=()=>{c.classList.remove(e.lightboxImageLoading)}),(t=>{const s=p[b.currentGallery].images.length,{title:n}=p[b.currentGallery].images[b.current],o=b.current+1;l.dataset.showcounter&&(g.style.removeProperty("display"),g.innerHTML=`<span class="${e.screenReaderOnly} ${e.screenReaderOnlyClasses}">Image ${o} of ${s}.</span><span aria-hidden="true">${o}/${s}</span>`),l.dataset.showcaptions?(y.style.removeProperty("display"),y.innerHTML=n):t.setAttribute("alt",n)})(a)}),b.timing)))},C=()=>{T(0),D()},T=e=>{c.setAttribute("style",`transform: translate3d(${e}px, 0, 0)`)},x=()=>{const e=b.touch.direction>0?"-200%":b.touch.direction<0?"200%":0;c.setAttribute("style",`transition-property: transform; transition-duration: ${b.timing}ms; transform: translate3d(${e}, 0, 0)`)},D=()=>{c.style.removeProperty("transition")};h.length>0&&(h.forEach((e=>{const t=e.dataset.gallery?e.dataset.gallery:"default";if(void 0===p[t]){const e=document.getElementById(t),s=e&&e.dataset.title?e.dataset.title:"untitled";p[t]={ref:t,title:s,images:[]}}const{averagecolor:s=null,mimetype:n,orientation:o,ref:i=null,srcset:r,title:l,url:a}=e.dataset;p[t].images.push({url:a,title:l,orientation:o,srcsetImages:r.split(","),mimetype:n,ref:i,gallery:t,averagecolor:s});const c=p[t].images.length-1;e.addEventListener("click",(e=>{e.preventDefault(),b.currentGallery=t,A(c),window.initialFocus=e.currentTarget}))})),window.addEventListener("keydown",(e=>{if(b.open){const t=focusableElements(l),s=t[0],n=t[1],o=t[t.length-1],i=t[t.length-2],r=()=>{(document.activeElement===s||s.disabled&&document.activeElement===n||s.disabled&&document.activeElement===l)&&(e.preventDefault(),o.disabled?i.focus():o.focus())},a=()=>{(document.activeElement===o||o.disabled&&document.activeElement===i||o.disabled&&document.activeElement===l)&&(e.preventDefault(),s.disabled?n.focus():s.focus()),s.disabled&&o.disabled&&(e.preventDefault(),m.focus())};"Tab"===e.key&&(1===t.length&&e.preventDefault(),e.shiftKey?r():a()),32!==e.keyCode&&"Enter"!==e.key||(document.activeElement===d&&(b.lastKeyboardControl="ArrowLeft"),document.activeElement===u&&(b.lastKeyboardControl="ArrowRight")),"Escape"===e.key&&f(),"ArrowLeft"===e.key&&(b.lastKeyboardControl="ArrowLeft",v()),"ArrowRight"===e.key&&(b.lastKeyboardControl="ArrowRight",L())}})),l.addEventListener("click",(e=>{e.target.id===`${t}-modal`&&(e.preventDefault(),f())})),d.addEventListener("click",(e=>{e.preventDefault(),v()})),u.addEventListener("click",(e=>{e.preventDefault(),L()})),m.addEventListener("click",(e=>{e.preventDefault(),f()})),c.addEventListener("touchmove",(e=>{b.touch.lastPos>b.touch.startPos?b.touch.direction=-1:b.touch.direction=1,b.touch.direction<0&&!w()&&T(e.touches[0].clientX-b.touch.startPos),b.touch.direction>0&&!E()&&T(e.touches[0].clientX-b.touch.startPos),E()&&w()&&T(e.touches[0].clientX-b.touch.startPos),b.touch.lastPos=e.touches[0].clientX}),{passive:!0}),c.addEventListener("touchstart",(e=>{b.touch.startPos=e.touches[0].clientX,b.touch.direction=0,D()}),{passive:!0}),c.addEventListener("touchend",(()=>{b.touch.lastPos-b.touch.startPos>b.touch.moveThreshold&&(E()?v():T(0)),b.touch.lastPos-b.touch.startPos<-b.touch.moveThreshold&&(w()?L():T(0))}),{passive:!0}))};window.initLightbox=initLightbox;