(function(){
"use strict";
function mount(){
const feed=document.querySelector(".bmd-radar-feed");if(!feed)return;
const select=feed.querySelector("[data-radar-feed-filter]");
const cards=[...feed.querySelectorAll("[data-radar-feed-slug]")];
const empty=feed.querySelector("[data-radar-feed-empty-filter]");
if(select&&empty)select.addEventListener("change",function(){
let count=0;cards.forEach(function(card){const show=!select.value||card.dataset.radarFeedSlug===select.value;card.hidden=!show;if(show)count++;});empty.hidden=count>0;
});
feed.querySelectorAll("[data-radar-feed-check]").forEach(function(a){
a.addEventListener("click",function(){
const value=a.dataset.radarFeedCheck||"";
const choice=document.querySelector(".bmd-radar [data-plugin]");
const target=document.querySelector(".bmd-radar [data-version]");
if(!choice||!target)return;
if([...choice.options].some(x=>x.value===value)){choice.value=value;choice.dispatchEvent(new Event("change",{bubbles:true}));}
target.focus({preventScroll:true});
});
});
}
if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",mount);else mount();
})();