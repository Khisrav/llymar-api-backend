import{_ as l,o as r,a,k as c}from"./index-DLEFopRY.js";const p={props:["type"]},i=["type"];function g(t,o,e,n,s,u){return r(),a("button",{type:e.type,class:"text-black bg-yellow-300 hover:bg-yellow-300 font-semibold focus:ring-4 rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 dark:bg-yellow dark:hover:bg-yellow-300"},[c(t.$slots,"default")],8,i)}const f=l(p,[["render",g]]),y={props:["type","placeholder","required","modelValue"],emits:["update:modelValue"]},b=["value","type","placeholder","required"];function _(t,o,e,n,s,u){return r(),a("input",{value:e.modelValue,onInput:o[0]||(o[0]=d=>t.$emit("update:modelValue",d.target.value)),type:e.type,placeholder:e.placeholder,required:e.required?!0:null,class:"bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"},null,40,b)}const h=l(y,[["render",_]]);export{f as B,h as I};
