<!-- resources/js/components/DoctorRequest.vue -->
<template>
   <form @submit.prevent="handleSubmit">
     <!-- 1) اختيار الطبيب (يُخفي إذا استُخدم doctorId) -->
     <div v-if="!doctorId" class="card mb-4">
       <div class="card-header bg-primary text-white">البيانات الأساسية</div>
       <div class="card-body">
         <label class="form-label">الطبيب</label>
         <Select
           v-model="selectedDoctor"
           :options="doctors"
           label="label"
           :reduce="d => d"
           placeholder="ابحث عن طبيب…"
           @search="fetchDoctors"
         />
       </div>
     </div>
 
     <!-- بقية الصفحة بعد اختيار الطبيب -->
     <template v-if="selectedDoctor">
       <!-- 2) البريدات الإلكترونية -->
       <div class="card mb-4">
         <div class="card-header bg-primary text-white">البريدات الإلكترونية</div>
         <div class="card-body">
           <label class="form-label">اختر بريد إلكتروني</label>
           <Select
             v-model="selectedEmail"
             :options="availableEmails"
             label="label"
             :reduce="e => e.email"
             placeholder="ابحث عن بريد…"
             @search="fetchEmails"
           />
 
           <!-- زر إضافة بريد جديد -->
           <!-- <button
             type="button"
             class="btn btn-outline-primary mt-2"
             @click="openEmailModal"
           >
             <i class="fa fa-plus me-1"></i> بريد جديد
           </button>
  -->
           <!-- قائمة البريدات المختارة -->
           <ul class="list-group mt-3" v-if="addedEmails.length">
             <li
               v-for="(email, i) in addedEmails"
               :key="i"
               class="list-group-item d-flex justify-content-between align-items-center"
             >
               {{ email }}
               <button class="btn btn-sm btn-danger" @click="removeEmail(i)">
                 حذف
               </button>
             </li>
           </ul>
 
           <div class="mt-3 text-end h5">
             إجمالي البريدات: {{ totalAmount.toFixed(2) }} د.ل
           </div>
         </div>
       </div>
 
       <!-- 3) الدول -->
       <div class="card mb-4">
         <div class="card-header bg-primary text-white">الدول المستهدفة</div>
         <div class="card-body">
           <label class="form-label">اختر مجموعة دول</label>
           <Select
             v-model="selectedCountries"
             :options="availableCountries"
             label="label"
             :reduce="c => c.id"
             multiple
             placeholder="ابحث عن دول…"
             @search="fetchCountries"
           />
         </div>
       </div>
 
       <!-- 4) الخدمات -->
       <div class="card mb-4">
         <div class="card-header bg-primary text-white">الطلبات الإضافية</div>
         <div class="card-body">
           <label class="form-label">ابحث واختر خدمة</label>
           <Select
             v-model="selectedService"
             :options="filteredServices"
             label="label"
             :reduce="s => s"
             placeholder="ابحث عن خدمة…"
             @search="fetchServices"
           />
 
           <ul class="list-group mt-3" v-if="selectedServices.length">
             <li
               v-for="(s, i) in selectedServices"
               :key="i"
               class="list-group-item"
             >
               <div class="mb-2">{{ s.label }} — {{ s.amount.toFixed(2) }} د.ل</div>
               <div class="d-flex align-items-center gap-2">
                 <input type="file" @change="onServiceFileChange($event, i)" />
                 <span v-if="s.file" class="small text-muted">
                   مرفق: {{ s.file.name }}
                 </span>
                 <button class="btn btn-sm btn-danger ms-auto" @click="removeService(i)">
                   حذف
                 </button>
               </div>
             </li>
           </ul>
 
           <div class="mt-3 text-end h5">
             إجمالي الخدمات: {{ totalServicesAmount.toFixed(2) }} د.ل
           </div>
         </div>
       </div>
 
       <!-- 5) معلومات إضافية -->
       <div class="card mb-4">
         <div class="card-header bg-primary text-white">معلومات إضافية</div>
         <div class="card-body">
           <div class="mb-3">
             <label class="form-label">ملاحظات</label>
             <textarea
               v-model="notes"
               class="form-control"
               rows="3"
               placeholder="أضف ملاحظات…"
             ></textarea>
           </div>
           <div class="form-check">
             <input
               class="form-check-input"
               type="checkbox"
               v-model="extractedBefore"
               id="extractedBefore"
             />
             <label class="form-check-label" for="extractedBefore">
               هل سبق له استخراج ملفات سابقاً؟
             </label>
           </div>
         </div>
       </div>
 
       <!-- 6) الإجمالي / إرسال -->
       <div class="card mb-4">
         <div class="card-body text-end">
           <div class="alert border-primary text-primary mb-3">
             <strong style="font-size:20px;">
               الإجمالي الكلي: {{ grandTotal.toFixed(2) }} د.ل
             </strong>
           </div>
           <button type="submit" class="btn btn-primary" :disabled="submitting">
             {{ submitting ? 'جاري الإرسال…' : 'إرسال النموذج' }}
           </button>
         </div>
       </div>
     </template>
 
     <!-- Modal: إضافة بريد جديد -->
     <div
       class="modal fade"
       :class="{ show: showEmailModal }"
       style="display: block;"
       v-if="showEmailModal"
       @click.self="closeEmailModal"
     >
       <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
           <div class="modal-header bg-primary text-white">
             <h5 class="modal-title">إضافة بريد إلكتروني جديد</h5>
             <button class="btn-close" @click="closeEmailModal"></button>
           </div>
           <div class="modal-body">
             <input
               type="email"
               class="form-control"
               v-model.trim="newEmailInput"
               placeholder="example@mail.com"
               @keydown.enter.prevent="saveNewEmail"
             />
           </div>
           <div class="modal-footer">
             <button class="btn btn-secondary" @click="closeEmailModal">إلغاء</button>
             <button class="btn btn-primary" @click="saveNewEmail">حفظ</button>
           </div>
         </div>
       </div>
       <div class="modal-backdrop fade show"></div>
     </div>
   </form>
 </template>
 
 <script setup>
 import { ref, watch, computed, onMounted } from 'vue'
 import axios from 'axios'
 import Swal from 'sweetalert2'
 import Select from 'vue3-select'
 import 'vue3-select/dist/vue3-select.css'
 
 // props
 const props = defineProps({
   doctorId: {
     type: [Number, String],
     default: null
   }
 })
 
 // reactive state
 const selectedDoctor   = ref(null)
 const doctors          = ref([])
 
 const selectedEmail    = ref(null)
 const availableEmails  = ref([])
 const addedEmails      = ref([])
 
 const selectedCountries  = ref([])
 const availableCountries = ref([])
 
 const selectedService  = ref(null)
 const servicesFull     = ref([])
 const filteredServices = ref([])
 const selectedServices = ref([])
 
 const notes           = ref('')
 const extractedBefore = ref(false)
 
 const unitPrice = ref(0)
 const submitting = ref(false)
 
 // modal state
 const showEmailModal = ref(false)
 const newEmailInput  = ref('')
 
 function openEmailModal() { showEmailModal.value = true }
 function closeEmailModal() { newEmailInput.value = ''; showEmailModal.value = false }
 
 // if doctorId passed, fetch doctor once
 onMounted(async () => {
   if (props.doctorId) {
     const { data } = await axios.get(`/api/doctors/${props.doctorId}`)
     // API should return { id, name, code, type }
     selectedDoctor.value = {
       ...data,
       label: `${data.name} (${data.code})`
     }
   }
 })
 
 // when doctor changes, load price & services
 watch(selectedDoctor, async doc => {
   if (!doc?.type) return
 
   // unit price
   const map = { libyan:85, palestinian:86, foreign:87 }
   const { data } = await axios.get(`/api/pricing/${map[doc.type]}`)
   unitPrice.value = Number(data.amount)
 
   // available services
   const srv = await axios.get('/api/pricings', {
     params: { type:'service', doctor_type: doc.type }
   })
   servicesFull.value = srv.data
     .filter(p => ![85,86,87].includes(p.id))
     .map(p => ({
       id: p.id,
       name: p.name,
       amount: Number(p.amount),
       label: `${p.name} (${Number(p.amount).toFixed(2)} د.ل)`,
       file: null
     }))
   filteredServices.value = [...servicesFull.value]
 })
 
 // email & service watchers
 watch(selectedEmail, em => {
   if (em && !addedEmails.value.includes(em)) addedEmails.value.push(em)
   selectedEmail.value = null
 })
 watch(selectedService, svc => {
   if (svc) selectedServices.value.push({ ...svc })
   selectedService.value = null
 })
 
 // totals
 const totalAmount = computed(() => unitPrice.value * addedEmails.value.length)
 const totalServicesAmount = computed(() =>
   selectedServices.value.reduce((sum, s) => sum + s.amount, 0)
 )
 const grandTotal = computed(() => totalAmount.value + totalServicesAmount.value)
 
 // fetch helpers
 function fetchDoctors(q) {
   axios.get('/api/doctors', { params:{ search:q } })
     .then(r => {
       doctors.value = r.data.map(d => ({ ...d, label:`${d.name} (${d.code})` }))
     })
 }
 function fetchEmails(q) {
   axios.get('/api/emails', { params:{ search:q } })
     .then(r => {
       availableEmails.value = r.data.map(e => ({ email:e.email, label:e.email }))
     })
 }
 function fetchCountries(q) {
   axios.get('/api/countries', { params:{ search:q } })
     .then(r => {
       availableCountries.value = r.data.map(c => ({ id:c.id, label:c.name }))
     })
 }
 function fetchServices(q) {
   const str = q.toLowerCase()
   filteredServices.value = servicesFull.value.filter(s =>
     s.label.toLowerCase().includes(str)
   )
 }
 
 // remove helpers
 function removeEmail(i) { addedEmails.value.splice(i,1) }
 function removeService(i) { selectedServices.value.splice(i,1) }
 function onServiceFileChange(e,i) {
   const f = e.target.files[0]
   if (f) selectedServices.value[i].file = f
 }
 
 // save new email
 async function saveNewEmail() {
   const email = newEmailInput.value.trim().toLowerCase()
   const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
   if (!re.test(email)) {
     return Swal.fire('تنبيه','صيغة البريد غير صحيحة','warning')
   }
   try {
     const { data } = await axios.post('/api/emails', { email })
     availableEmails.value.push({ email:data.email, label:data.email })
     addedEmails.value.push(data.email)
     closeEmailModal()
   }
   catch(err) {
     if (err.response?.status === 422) {
       Swal.fire('خطأ','البريد موجود مسبقاً','error')
       closeEmailModal()
     }
   }
 }
 
 // submit
 async function handleSubmit() {
   if (!addedEmails.value.length) {
     return Swal.fire('تنبيه','أضف بريداً واحداً على الأقل','warning')
   }
   if (!selectedServices.value.length) {
     return Swal.fire('تنبيه','اختر خدمة واحدة على الأقل','warning')
   }
 
   submitting.value = true
   const form = new FormData()
   form.append('doctor_id', selectedDoctor.value.id)
   addedEmails.value.forEach(e => form.append('emails[]', e))
   selectedCountries.value.forEach(c => form.append('countries[]', c))
   form.append('notes', notes.value)
   form.append('extracted_before', extractedBefore.value ? '1':'0')
   selectedServices.value.forEach((s,i) => {
     form.append(`services[${i}][id]`, s.id)
     form.append(`services[${i}][amount]`, s.amount)
     if (s.file) form.append(`services[${i}][file]`, s.file)
   })
 
   try {
     await axios.post('/api/doctor-mails', form, {
       headers:{ 'Content-Type':'multipart/form-data' }
     })
     await Swal.fire({ icon:'success', title:'تم الحفظ بنجاح', timer:1500, showConfirmButton:false })

     if(props.doctorId) {
       // if doctorId passed, reload the page
      window.loccation = 'doctor/dashboard?requests=1';
     }
     else {
       // if not, redirect to the list page
       window.location = '/admin/doctor-mails'
     }

   }
   catch(e) {
     console.error(e)
   }
   finally {
     submitting.value = false
   }
 }
 </script>
 
 <style>
 /* force full width on vue3-select */
 .vue3-select,
 .vue3-select .vs__dropdown-toggle {
   width: 100% !important;
 }
 </style>
 