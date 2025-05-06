<!-- resources/js/components/DoctorRequest.vue -->
<template>
  <form @submit.prevent="handleSubmit">
    <!-- اختيار الطبيب -->
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

    <template v-if="selectedDoctor">
      <!-- الطلبات -->
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">الطلبات</div>
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
            <li v-for="(s, i) in selectedServices" :key="i" class="list-group-item">
              <div class="mb-2">{{ s.label }} — {{ s.amount.toFixed(2) }} د.ل</div>

              <div v-if="[43, 44, 45].includes(s.id)" class="mb-2">
                <div class="form-check">
                  <input class="form-check-input" type="radio" :name="'work_mention_' + i" value="with" v-model="s.work_mention" />
                  <label class="form-check-label">مع ذكر جهة العمل</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" :name="'work_mention_' + i" value="without" v-model="s.work_mention" />
                  <label class="form-check-label">دون ذكر جهة العمل</label>
                </div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <input type="file" @change="onServiceFileChange($event, i)" />
                <span v-if="s.file" class="small text-muted">مرفق: {{ s.file.name }}</span>
                <button class="btn btn-sm btn-danger ms-auto" @click="removeService(i)">حذف</button>
              </div>
            </li>
          </ul>

          <div class="mt-3 text-end h5">إجمالي الخدمات: {{ totalServicesAmount.toFixed(2) }} د.ل</div>
        </div>
      </div>

      <!-- البريد الإلكتروني -->
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">البريد الإلكتروني</div>
        <div class="card-body">
          <label class="form-label">اختر أو أدخل بريد إلكتروني</label>
          <Select
            v-model="selectedEmail"
            :options="availableEmails"
            label="label"
            placeholder="اختر بريد…"
            @search="fetchEmails"
          />
          <div class="input-group mt-2">
            <input type="email" v-model="newEmail" class="form-control" placeholder="أدخل بريد جديد…" />
            <button class="btn btn-outline-success" @click.prevent="addNewEmail">إضافة</button>
          </div>

          <table class="table mt-3" v-if="addedEmails.length">
            <thead>
              <tr><th>البريد</th><th>التحكم</th></tr>
            </thead>
            <tbody>
              <tr v-for="(email, i) in addedEmails" :key="i">
                <td>{{ email }}</td>
                <td><button class="btn btn-sm btn-danger" @click="removeEmail(i)">حذف</button></td>
              </tr>
            </tbody>
          </table>

          <div class="mt-3 text-end h5">إجمالي البريد: {{ totalAmount.toFixed(2) }} د.ل</div>
        </div>
      </div>

      <!-- الدول -->
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">الدول المستهدفة</div>
        <div class="card-body">
          <label class="form-label">اختر مجموعة دول</label>
          <Select
            v-model="selectedCountries"
            :options="availableCountries"
            label="label"
            :reduce="c => c"
            multiple
            placeholder="اختر دول…"
            @search="fetchCountries"
          />
        </div>
      </div>

      <!-- الملاحظات -->
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">معلومات إضافية</div>
        <div class="card-body">
          <label class="form-label">ملاحظات</label>
          <textarea v-model="notes" class="form-control" rows="3" placeholder="أضف ملاحظات…"></textarea>
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" v-model="extractedBefore" id="extractedBefore" />
            <label class="form-check-label" for="extractedBefore">هل سبق لك استخراج ملفات؟</label>
          </div>
          <div v-if="extractedBefore" class="mt-3">
            <label class="form-label">أدخل آخر سنة استخراج</label>
            <input type="number" v-model="lastExtractYear" class="form-control" placeholder="مثل: 2022" min="1900" max="2099" />
          </div>
        </div>
      </div>

      <!-- الإرسال -->
      <div class="card mb-4">
        <div class="card-body text-end">
          <div class="alert border-primary text-primary mb-3">
            <strong style="font-size:20px;">الإجمالي الكلي: {{ grandTotal.toFixed(2) }} د.ل</strong>
          </div>
          <button type="submit" class="btn btn-primary" :disabled="submitting">
            {{ submitting ? 'جاري الإرسال…' : 'إرسال النموذج' }}
          </button>
        </div>
      </div>
    </template>
  </form>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import Select from 'vue3-select'
import 'vue3-select/dist/vue3-select.css'

const props = defineProps({ doctorId: [Number, String] })

const selectedDoctor = ref(null)
const doctors = ref([])
const selectedEmail = ref(null)
const newEmail = ref('')
const availableEmails = ref([])
const addedEmails = ref([])
const selectedCountries = ref([])
const availableCountries = ref([])
const selectedService = ref(null)
const servicesFull = ref([])
const filteredServices = ref([])
const selectedServices = ref([])
const notes = ref('')
const extractedBefore = ref(false)
const lastExtractYear = ref('')
const unitPrice = ref(0)
const submitting = ref(false)

onMounted(async () => {
  if (props.doctorId) {
    const { data } = await axios.get(`/api/doctors/${props.doctorId}`)
    selectedDoctor.value = { ...data, label: `${data.name} (${data.code})` }
  }
})

watch(selectedDoctor, async doc => {
  if (!doc?.type) return
  const map = { libyan: 85, palestinian: 86, foreign: 87 }
  const { data } = await axios.get(`/api/pricing/${map[doc.type]}`)
  unitPrice.value = Number(data.amount)

  const srv = await axios.get('/api/pricings', {
    params: { type: 'service', doctor_type: doc.type }
  })
  servicesFull.value = srv.data
    .filter(p => ![85, 86, 87].includes(p.id))
    .map(p => ({
      id: p.id,
      name: p.name,
      amount: Number(p.amount),
      label: `${p.name} (${Number(p.amount).toFixed(2)} د.ل)`,
      file: null,
      work_mention: null
    }))
  filteredServices.value = [...servicesFull.value]
})

watch(selectedEmail, em => {
  if (em && !addedEmails.value.includes(em.email)) addedEmails.value.push(em.email)
  selectedEmail.value = null
})

watch(selectedService, svc => {
  if (svc) selectedServices.value.push({ ...svc, work_mention: null })
  selectedService.value = null
})

function fetchDoctors(q) {
  axios.get('/api/doctors', { params: { search: q } }).then(r => {
    doctors.value = r.data.map(d => ({ ...d, label: `${d.name} (${d.code})` }))
  })
}
function fetchEmails(q) {
  axios.get('/api/emails', { params: { search: q } }).then(r => {
    availableEmails.value = r.data.map(e => ({ email: e.email, label: e.email }))
  })
}
function fetchCountries(q) {
  axios.get('/api/countries', { params: { search: q } }).then(r => {
    availableCountries.value = r.data.map(c => ({ id: c.id, label: c.name }))
  })
}
function fetchServices(q) {
  const str = q.toLowerCase()
  filteredServices.value = servicesFull.value.filter(s =>
    s.label.toLowerCase().includes(str)
  )
}

function removeEmail(i) { addedEmails.value.splice(i, 1) }
function removeService(i) { selectedServices.value.splice(i, 1) }
function addNewEmail() {
  const email = newEmail.value.trim()
  if (email && !addedEmails.value.includes(email)) {
    addedEmails.value.push(email)
    newEmail.value = ''
  }
}
function onServiceFileChange(e, i) {
  const f = e.target.files[0]
  if (f) selectedServices.value[i].file = f
}

const totalAmount = computed(() => unitPrice.value * addedEmails.value.length)
const totalServicesAmount = computed(() =>
  selectedServices.value.reduce((sum, s) => sum + s.amount, 0)
)
const grandTotal = computed(() => totalAmount.value + totalServicesAmount.value)

async function handleSubmit() {
  if (!addedEmails.value.length) {
    return Swal.fire('تنبيه', 'أضف بريداً واحداً على الأقل', 'warning')
  }

  if (!selectedServices.value.length) {
    return Swal.fire('تنبيه', 'اختر خدمة واحدة على الأقل', 'warning')
  }

  for (let s of selectedServices.value) {
    if ([43, 44, 45].includes(s.id) && !s.work_mention) {
      return Swal.fire('تنبيه', 'يرجى اختيار "مع أو دون جهة العمل" للخدمة المطلوبة', 'warning')
    }
  }

  submitting.value = true
  const form = new FormData()
  form.append('doctor_id', selectedDoctor.value.id)
  addedEmails.value.forEach(e => form.append('emails[]', e))
  selectedCountries.value.forEach(c => form.append('countries[]', c.id))
  form.append('notes', notes.value)
  form.append('extracted_before', extractedBefore.value ? '1' : '0')
  if (extractedBefore.value && lastExtractYear.value) {
    form.append('last_extract_year', lastExtractYear.value)
  }

  selectedServices.value.forEach((s, i) => {
    form.append(`services[${i}][id]`, s.id)
    form.append(`services[${i}][amount]`, s.amount)
    if (s.file) form.append(`services[${i}][file]`, s.file)
    if ([43, 44, 45].includes(s.id) && s.work_mention) {
      form.append(`services[${i}][work_mention]`, s.work_mention)
    }
  })

  try {
    await axios.post('/api/doctor-mails', form, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    await Swal.fire({ icon: 'success', title: 'تم الحفظ بنجاح', timer: 1500, showConfirmButton: false })

    if (props.doctorId) {
      window.location = '/doctor/dashboard?requests=1'
    } else {
      window.location = '/admin/doctor-mails'
    }
  } catch (e) {
    console.error(e)
  } finally {
    submitting.value = false
  }
}
</script>

<style>
.vue3-select,
.vue3-select .vs__dropdown-toggle {
  width: 100% !important;
}
</style>
