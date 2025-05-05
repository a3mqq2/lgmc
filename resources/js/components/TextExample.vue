<template>
   <div class="p-4">
     <h2 class="mb-2 text-lg font-bold">مثال على حقل نصي</h2>
     <input
       type="text"
       v-model="message"
       class="border border-gray-300 rounded px-3 py-2 w-full"
       placeholder="اكتب شي هنا..."
     />
     <p class="mt-3 text-gray-700">النص المدخل: {{ message }}</p>
   </div>
 </template>
 
 <script setup>
 import { ref } from 'vue'
 const message = ref('')
 </script>
 