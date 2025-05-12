<template>
  <form @submit.prevent="handleSubmit">
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
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">الطلبات</div>
        <div class="card-body">
          <label class="form-label">اختر خدمة</label>
          <Select
            v-model="selectedService"
            :options="filteredServices"
            label="label"
            :reduce="s => s"
            placeholder="اختر خدمة…"
            @search="fetchServices"
          />

          <ul class="list-group mt-3" v-if="selectedServices.length">
            <li v-for="(s, i) in selectedServices" :key="i" class="list-group-item">
              <div class="mb-2">{{ s.label }} — {{ s.amount.toFixed(2) }} د.ل</div>
              <div v-if="[45, 46, 47].includes(s.id)" class="mb-2">
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

      <div class="card mb-4">
        <div class="card-header bg-primary text-white">البريد الإلكتروني</div>
        <div class="card-body">
          <label class="form-label">اختر بريد إلكتروني</label>
          <Select
            v-model="selectedEmail"
            :options="availableEmails"
            label="label"
            :reduce="e => e"
            placeholder="اختر بريد…"
          />
          <div class="input-group mt-2">
            <input type="email" v-model="newEmail" class="form-control" placeholder="بريد جديد…" />
            <button class="btn btn-outline-success" @click.prevent="addNewEmail">إضافة</button>
          </div>
          <table class="table mt-3" v-if="addedEmails.length">
            <thead><tr><th>البريد</th><th>تحكم</th></tr></thead>
            <tbody>
              <tr v-for="(email, i) in addedEmails" :key="i">
                <td>{{ email.value }}</td>
                <td><button class="btn btn-sm btn-danger" @click="removeEmail(i)">حذف</button></td>
              </tr>
            </tbody>
          </table>
          <div class="mt-3 text-end h5">إجمالي البريد: {{ totalAmount.toFixed(2) }} د.ل</div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header bg-primary text-white">الدول المستهدفة</div>
        <div class="card-body">
          <label class="form-label">اختر أو أدخل دولة</label>
          <Select
            v-model="selectedCountry"
            :options="availableCountries"
            label="label"
            :reduce="c => c"
            placeholder="اختر دولة…"
            @search="fetchCountries"
          />
          <div class="input-group mt-2">
            <input v-model="newCountry" class="form-control" placeholder="دولة جديدة…" />
            <button class="btn btn-outline-success" @click.prevent="addNewCountry">إضافة</button>
          </div>
          <table class="table mt-3" v-if="selectedCountries.length">
            <thead><tr><th>الدولة</th><th>تحكم</th></tr></thead>
            <tbody>
              <tr v-for="(c, i) in selectedCountries" :key="c.id">
                <td>{{ c.label }}</td>
                <td><button class="btn btn-sm btn-danger" @click="removeCountry(i)">حذف</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header bg-primary text-white">ملاحظات</div>
        <div class="card-body">
          <textarea v-model="notes" class="form-control" rows="3" placeholder="ملاحظات…"></textarea>
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" v-model="extractedBefore" id="extractedBefore" />
            <label class="form-check-label" for="extractedBefore">سبق استخراج أوراق؟</label>
          </div>
          <div v-if="extractedBefore" class="mt-3">
            <input type="number" v-model="lastExtractYear" class="form-control" placeholder="مثال: 2022" />
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-body text-end">
          <div class="alert alert-primary">الإجمالي الكلي: {{ grandTotal.toFixed(2) }} د.ل</div>
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

const selectedDoctor    = ref(null)
const doctors           = ref([])

const servicesFull      = ref([])
const filteredServices  = ref([])
const selectedService   = ref(null)
const selectedServices  = ref([])

const availableEmails   = ref([])
const selectedEmail     = ref(null)
const newEmail          = ref('')
const addedEmails       = ref([])

const availableCountries= ref([])
const selectedCountry   = ref(null)
const newCountry        = ref('')
const selectedCountries = ref([])

const notes             = ref('')
const extractedBefore   = ref(false)
const lastExtractYear   = ref('')
const unitPrice         = ref(0)
const submitting        = ref(false)

onMounted(async () => {
  if (props.doctorId) {
    const { data } = await axios.get(`/api/doctors/${props.doctorId}`)
    selectedDoctor.value = { ...data, label: `${data.name} (${data.code})` }
  }
})

watch(selectedDoctor, async doc => {
  if (!doc?.type) return

  const { data: emailPricing } = await axios.get('/api/pricings', {
    params: { type: 'email', doctor_type: doc.type }
  })
  unitPrice.value = emailPricing.length ? Number(emailPricing[0].amount) : 0
  console.log(unitPrice);
  console.log(unitPrice )
  const { data: mailPricings } = await axios.get('/api/pricings', {
    params: { type: 'mail', doctor_type: doc.type }
  })
  servicesFull.value = mailPricings.map(p => ({
    id: p.id,
    label: `${p.name} (${Number(p.amount).toFixed(2)} د.ل)`,
    amount: Number(p.amount),
    file: null,
    work_mention: null
  }))
  filteredServices.value = servicesFull.value

  const { data: emails } = await axios.get('/api/emails')
  availableEmails.value = emails.map(e => ({ label: e.email, value: e.email }))
})

function fetchServices(search) {
  const q = (search || '').toLowerCase()
  filteredServices.value = servicesFull.value.filter(s =>
    s.label.toLowerCase().includes(q)
  )
}

watch(selectedService, svc => {
  if (svc) selectedServices.value.push({ ...svc })
  selectedService.value = null
})

watch(selectedEmail, em => {
  if (em && !addedEmails.value.includes(em)) addedEmails.value.push(em)
  selectedEmail.value = null
})

function fetchDoctors(q) {
  axios.get('/api/doctors', { params: { search: q } })
    .then(r => {
      doctors.value = r.data.map(d => ({ ...d, label: `${d.name} (${d.code})` }))
    })
}

function addNewEmail() {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!re.test(newEmail.value)) return Swal.fire('خطأ', 'البريد غير صالح', 'error')
  if (!addedEmails.value.includes(newEmail.value)) addedEmails.value.push(newEmail.value)
  newEmail.value = ''
}

function removeService(i) { selectedServices.value.splice(i, 1) }
function removeEmail(i)   { addedEmails.value.splice(i, 1) }
function removeCountry(i) { selectedCountries.value.splice(i, 1) }

const totalAmount         = computed(() => unitPrice.value * addedEmails.value.length)
const totalServicesAmount = computed(() => selectedServices.value.reduce((sum, s) => sum + s.amount, 0))
const grandTotal          = computed(() => totalAmount.value + totalServicesAmount.value)

async function handleSubmit() {
  if (!addedEmails.value.length) return Swal.fire('تنبيه','أضف بريداً واحداً على الأقل','warning')
  if (!selectedServices.value.length) return Swal.fire('تنبيه','اختر خدمة واحدة على الأقل','warning')
  for (let s of selectedServices.value) {
    if ([45,46,47].includes(s.id) && !s.work_mention) return Swal.fire('تنبيه','يرجى اختيار جهة العمل للخدمة','warning')
  }

  submitting.value = true
  const form = new FormData()
  form.append('doctor_id', selectedDoctor.value.id)
  addedEmails.value.forEach(e => form.append('emails[]', e))
  selectedCountries.value.forEach(c => form.append('countries[]', c.id))
  form.append('notes', notes.value)
  form.append('extracted_before', extractedBefore.value ? '1' : '0')
  if (extractedBefore.value && lastExtractYear.value) form.append('last_extract_year', lastExtractYear.value)
  selectedServices.value.forEach((s, i) => {
    form.append(`services[${i}][id]`, s.id)
    form.append(`services[${i}][amount]`, s.amount)
    if (s.file) form.append(`services[${i}][file]`, s.file)
    if (s.work_mention) form.append(`services[${i}][work_mention]`, s.work_mention)
  })

  try {
    await axios.post('/api/doctor-mails', form)
    await Swal.fire({ icon:'success', title:'تم الحفظ بنجاح', timer:1500, showConfirmButton:false })
    window.location = props.doctorId
      ? '/doctor/dashboard?requests=1'
      : '/admin/doctor-mails'
  } catch (e) {
    console.error(e)
  } finally {
    submitting.value = false
  }
}
</script>

<style>
.vue3-select,
.vue3-select .vs__dropdown-toggle { width: 100% !important; }
</style>
