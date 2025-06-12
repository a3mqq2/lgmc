<template>
  <div class="">
    <!-- Progress Steps -->
    <div class="steps-progress mb-4">
      <div class="step-progress-bar">
        <div 
          v-for="(step, index) in steps" 
          :key="index"
          class="step-item"
          :class="{
            'active': currentStep === index + 1,
            'completed': currentStep > index + 1,
            'disabled': currentStep < index + 1
          }"
        >
          <div class="step-circle">
            <i v-if="currentStep > index + 1" class="fas fa-check"></i>
            <span v-else>{{ index + 1 }}</span>
          </div>
          <div class="step-label">{{ step.title }}</div>
        </div>
      </div>
    </div>

    <form @submit.prevent="handleSubmit">
      <!-- Step 1: بيانات الطبيب -->
      <div v-show="currentStep === 1" class="step-content">
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-user-md me-2"></i>الخطوة الأولى: اختيار الطبيب
          </div>
          <div class="card-body">
            <div v-if="!doctorId">
              <label class="form-label required">الطبيب</label>
              <Select
                v-model="selectedDoctor"
                :options="doctors"
                label="label"
                :reduce="d => d"
                placeholder="ابحث عن طبيب…"
                @search="fetchDoctors"
                :loading="doctorsLoading"
              />
            </div>
            <div v-else-if="selectedDoctor" class="doctor-info p-3 bg-light rounded">
              <h5><i class="fas fa-user-md me-2"></i>{{ selectedDoctor.name }}</h5>
              <p class="mb-0 text-muted">كود الطبيب: {{ selectedDoctor.code }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 2: الخدمات -->
      <div v-show="currentStep === 2" class="step-content">
        <div class="card mb-4" style="z-index: 10;">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-concierge-bell me-2"></i>الخطوة الثانية: اختيار الخدمات
          </div>
          <div class="card-body">
            <label class="form-label required">اختر خدمة</label>
            <Select
              v-model="selectedService"
              :options="filteredServices"
              label="label"
              :reduce="s => s"
              placeholder="اختر خدمة…"
              @search="fetchServices"
              :loading="servicesLoading"
            />

            <!-- قائمة الخدمات المختارة -->
            <div v-if="selectedServices.length" class="mt-4">
              <h6 class="mb-3">الخدمات المختارة:</h6>
              <div class="row">
                <div v-for="(service, index) in selectedServices" :key="index" class="col-md-6 mb-3">
                  <div class="card border-primary">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">{{ service.label.split('(')[0] }}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="removeService(index)">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      <div class="text-primary fw-bold mb-3">{{ service.amount.toFixed(2) }} د.ل</div>
                      
                      <!-- خيارات جهة العمل للخدمات المحددة -->
                      <div v-if="[8,23,36].includes(service.id)" class="mb-3">
                        <div class="form-check">
                          <input 
                            class="form-check-input" 
                            type="radio" 
                            :name="'work_mention_' + index" 
                            value="with" 
                            v-model="service.work_mention" 
                          />
                          <label class="form-check-label small d-block">مع ذكر جهة العمل</label>
                        </div>
                        <div class="form-check">
                          <input 
                            class="form-check-input" 
                            type="radio" 
                            :name="'work_mention_' + index" 
                            value="without" 
                            v-model="service.work_mention" 
                          />
                          <label class="form-check-label small">دون ذكر جهة العمل</label>
                        </div>
                        <label class="form-label small">جهة العمل:</label>
                        
                      </div>

                      <!-- رفع الملفات المحسن -->
                      <div class="mb-2">
                        <label class="form-label small">
                          {{ service.file_name || 'مرفق' }}
                          <span v-if="service.file_required" class="text-danger">*</span>
                          <span v-else class="text-muted">(اختياري)</span>
                        </label>
                        
                        <input 
                          type="file" 
                          class="form-control form-control-sm" 
                          :class="{ 
                            'is-invalid': service.file_required && !service.file,
                            'is-valid': service.file 
                          }"
                          @change="onServiceFileChange($event, index)" 
                          accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                          :required="service.file_required"
                        />
                        
                        <!-- عرض اسم الملف المرفوع -->
                        <div v-if="service.file" class="small text-success mt-1 d-flex align-items-center">
                          <i class="fas fa-file me-1"></i>
                          <span class="flex-grow-1">{{ service.file.name }}</span>
                          <button 
                            type="button" 
                            class="btn btn-sm btn-outline-danger ms-2" 
                            @click="removeServiceFile(index)"
                            title="إزالة الملف"
                          >
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                        
                        <!-- رسالة خطأ للملفات المطلوبة -->
                        <div v-if="service.file_required && !service.file" class="invalid-feedback">
                          {{ service.file_name || 'هذا المرفق' }} مطلوب
                        </div>
                        
                        <!-- معلومات إضافية عن نوع الملف -->
                        <small class="text-muted d-block mt-1">
                          الملفات المدعومة: PDF, JPG, PNG, DOC, DOCX (حد أقصى: 5MB)
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- تحذير إذا كان هناك مرفقات مطلوبة غير مرفوعة -->
              <div v-if="hasRequiredFilesNotUploaded" class="alert alert-warning d-flex align-items-center mb-3">
                <i class="fas fa-exclamation-triangle me-3"></i>
                <div>
                  <strong>تنبيه مهم</strong>
                  <span class="d-block">يرجى رفع جميع المرفقات المطلوبة قبل المتابعة</span>
                </div>
              </div>
              
              <div class="alert alert-info d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <i class="fas fa-calculator me-2"></i>
                  <span>إجمالي الخدمات:</span>
                </div>
                <strong class="fs-5">{{ totalServicesAmount.toFixed(2) }} د.ل</strong>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-4" style="z-index: 1;">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-sticky-note me-2"></i> تفاصيل الاوراق
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">يرجى ادخال اي تفاصيل مهمه عن الطلب</label>
              <textarea 
                v-model="notes" 
                class="form-control" 
                rows="3" 
                placeholder="أدخل أي ملاحظات إضافية…"
              ></textarea>
            </div>
            
            <div class="form-check mb-3">
              <input 
                class="form-check-input" 
                type="checkbox" 
                v-model="extractedBefore" 
                id="extractedBefore" 
              />
              <label class="form-check-label" for="extractedBefore">
                سبق استخراج أوراق ؟
              </label>
            </div>
            
            <div v-if="extractedBefore" class="mb-3">
              <label class="form-label">سنة آخر استخراج</label>
              <input 
                type="number" 
                v-model="lastExtractYear" 
                class="form-control" 
                placeholder="مثال: 2022"
                :min="1990"
                :max="new Date().getFullYear()"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Step 3: البريد الإلكتروني -->
      <div v-show="currentStep === 3" class="step-content">
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-envelope me-2"></i>الخطوة الثالثة: البريد الإلكتروني
            <span class="badge bg-light text-dark ms-2">{{ unitPrice.toFixed(2) }} د.ل لكل بريد</span>
          </div>
          <div class="card-body">
            <label class="form-label required">البريد الإلكتروني</label>
            <Select
              v-model="selectedEmailInput"
              :options="emailOptions"
              label="label"
              :reduce="e => e"
              placeholder="ابحث أو أدخل بريد إلكتروني جديد…"
              @search="handleEmailSearch"
              :taggable="true"
              @tag="addNewEmailFromTag"
              :loading="emailsLoading"
              createOptionText="إضافة البريد الجديد:"
            />
            
            <!-- الإيميلات المختارة -->
            <div v-if="addedEmails.length" class="mt-3">
              <h6 class="mb-2">الإيميلات المختارة:</h6>
              <div class="row">
                <div v-for="(email, index) in addedEmails" :key="index" class="col-md-6 mb-2">
                  <div class="d-flex align-items-center bg-light p-2 rounded">
                    <i class="fas fa-envelope text-primary me-2"></i>
                    <span class="flex-grow-1">{{ email.value }}</span>
                    <span v-if="email.isNew" class="badge bg-success me-2">جديد</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeEmail(index)">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="alert alert-info d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                  <i class="fas fa-envelope me-2"></i>
                  <span>إجمالي البريد ({{ addedEmails.length }} بريد):</span>
                </div>
                <strong class="fs-5">{{ totalEmailAmount.toFixed(2) }} د.ل</strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 4: الدول المستهدفة -->
      <div v-show="currentStep === 4" class="step-content">
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <i class="fas fa-globe me-2"></i>الخطوة الرابعة: الدول المستهدفة
          </div>
          <div class="card-body">
            <label class="form-label">الدول</label>
            <Select
              v-model="selectedCountryInput"
              :options="countryOptions"
              label="label"
              :reduce="c => c"
              placeholder="ابحث أو أدخل دولة جديدة…"
              @search="handleCountrySearch"
              :taggable="true"
              @tag="addNewCountryFromTag"
              :loading="countriesLoading"
              createOptionText="إضافة الدولة الجديدة:"
            />

            <!-- الدول المختارة -->
            <div v-if="selectedCountries.length" class="mt-3">
              <h6 class="mb-2">الدول المختارة:</h6>
              <div class="row">
                <div v-for="(country, index) in selectedCountries" :key="country.id" class="col-md-4 mb-2">
                  <div class="d-flex align-items-center bg-light p-2 rounded">
                    <i class="fas fa-flag text-primary me-2"></i>
                    <span class="flex-grow-1">{{ country.label }}</span>
                    <span v-if="country.isNew" class="badge bg-success me-2">جديد</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeCountry(index)">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Step 5: المعلومات الإضافية والمراجعة -->
      <div v-show="currentStep === 5" class="step-content">
        <!-- الملاحظات والمعلومات الإضافية -->
    

        <!-- ملخص الطلب -->
        <div class="card mb-4">
          <div class="card-header bg-success text-white">
            <i class="fas fa-clipboard-check me-2"></i>ملخص الطلب
          </div>
          <div class="card-body">
            <!-- معلومات الطبيب -->
            <div class="summary-section mb-4" v-if="selectedDoctor">
              <h6><i class="fas fa-user-md me-2"></i>الطبيب</h6>
              <p class="mb-0">{{ selectedDoctor.name }} ({{ selectedDoctor.code }})</p>
            </div>

            <!-- الخدمات -->
            <div class="summary-section mb-4" v-if="selectedServices.length">
              <h6><i class="fas fa-concierge-bell me-2"></i>الخدمات ({{ selectedServices.length }})</h6>
              <ul class="list-unstyled mb-0">
                <li v-for="service in selectedServices" :key="service.id" class="d-flex justify-content-between">
                  <span>{{ service.label.split('(')[0] }}</span>
                  <span class="text-primary fw-bold">{{ service.amount.toFixed(2) }} د.ل</span>
                </li>
              </ul>
            </div>

            <!-- الإيميلات -->
            <div class="summary-section mb-4" v-if="addedEmails.length">
              <h6><i class="fas fa-envelope me-2"></i>الإيميلات ({{ addedEmails.length }})</h6>
              <div class="d-flex justify-content-between">
                <span>{{ addedEmails.length }} بريد × {{ unitPrice.toFixed(2) }} د.ل</span>
                <span class="text-primary fw-bold">{{ totalEmailAmount.toFixed(2) }} د.ل</span>
              </div>
            </div>

            <!-- الدول -->
            <div class="summary-section mb-4" v-if="selectedCountries.length">
              <h6><i class="fas fa-globe me-2"></i>الدول المستهدفة</h6>
              <p class="mb-0">{{ selectedCountries.map(c => c.label).join('، ') }}</p>
            </div>

            <!-- الإجمالي -->
            <div class="alert alert-success mb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <i class="fas fa-calculator me-2"></i>
                  <strong>الإجمالي الكلي:</strong>
                </div>
                <div class="fs-4 fw-bold">{{ grandTotal.toFixed(2) }} د.ل</div>
              </div>
              <small class="d-block mt-2 opacity-75">
                <i class="fas fa-info-circle me-1"></i>
                خدمات: {{ totalServicesAmount.toFixed(2) }} د.ل + 
                بريد: {{ totalEmailAmount.toFixed(2) }} د.ل
              </small>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="step-navigation d-flex justify-content-between mt-4">
        <button 
          type="button" 
          class="btn btn-outline-secondary btn-lg px-4"
          @click="previousStep"
          :disabled="currentStep === 1"
        >
          <i class="fas fa-arrow-right me-2"></i>السابق
        </button>

        <div class="step-info text-center">
          <span class="text-muted">الخطوة {{ currentStep }} من {{ steps.length }}</span>
        </div>

        <button 
          v-if="currentStep < steps.length"
          type="button" 
          class="btn btn-primary btn-lg px-4"
          @click="nextStep"
          :disabled="!canProceedToNextStep"
        >
          التالي<i class="fas fa-arrow-left ms-2"></i>
        </button>

        <button 
          v-if="currentStep === steps.length"
          type="submit" 
          class="btn btn-success btn-lg px-4" 
          :disabled="submitting || !canSubmit"
        >
          <i v-if="submitting" class="fas fa-spinner fa-spin me-2"></i>
          <i v-else class="fas fa-paper-plane me-2"></i>
          {{ submitting ? 'جاري الإرسال…' : 'إرسال الطلب' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import Select from 'vue3-select'
import 'vue3-select/dist/vue3-select.css'

const props = defineProps({ 
  doctorId: [Number, String] 
})

// Steps configuration
const steps = ref([
  { title: 'اختيار الطبيب', icon: 'fas fa-user-md' },
  { title: 'اختيار الخدمات', icon: 'fas fa-concierge-bell' },
  { title: 'البريد الإلكتروني', icon: 'fas fa-envelope' },
  { title: 'الدول المستهدفة', icon: 'fas fa-globe' },
  { title: 'المراجعة والإرسال', icon: 'fas fa-check-circle' }
])

const currentStep = ref(1)

// حالة التحميل
const doctorsLoading = ref(false)
const servicesLoading = ref(false)
const emailsLoading = ref(false)
const countriesLoading = ref(false)
const submitting = ref(false)

// بيانات الطبيب
const selectedDoctor = ref(null)
const doctors = ref([])

// الخدمات
const servicesFull = ref([])
const filteredServices = ref([])
const selectedService = ref(null)
const selectedServices = ref([])

// البريد الإلكتروني المحسن
const baseEmails = ref([])
const emailOptions = ref([])
const selectedEmailInput = ref(null)
const addedEmails = ref([])
const unitPrice = ref(0)

// الدول المحسنة
const baseCountries = ref([])
const countryOptions = ref([])
const selectedCountryInput = ref(null)
const selectedCountries = ref([])

// معلومات إضافية
const notes = ref('')
const extractedBefore = ref(false)
const lastExtractYear = ref('')

// تحميل البيانات الأولية
onMounted(async () => {
  await Promise.all([
    loadBaseEmails(),
    loadBaseCountries()
  ])

  if (props.doctorId) {
    const { data } = await axios.get(`/api/doctors/${props.doctorId}`)
    selectedDoctor.value = { ...data, label: `${data.name} (${data.code})` }
  }
})

// Steps Navigation
function nextStep() {
  if (canProceedToNextStep.value && currentStep.value < steps.value.length) {
    currentStep.value++
    scrollToTop()
  }
}

function previousStep() {
  if (currentStep.value > 1) {
    currentStep.value--
    scrollToTop()
  }
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// تحديد إمكانية الانتقال للخطوة التالية
const canProceedToNextStep = computed(() => {
  switch (currentStep.value) {
    case 1:
      return selectedDoctor.value !== null
    case 2:
      return selectedServices.value.length > 0 && 
             selectedServices.value.every(s => 
               ![8,23,36].includes(s.id) || s.work_mention
             ) &&
             selectedServices.value.every(s => 
               !s.file_required || s.file
             )
    case 3:
      return addedEmails.value.length > 0
    case 4:
      return true // الدول اختيارية
    case 5:
      return true
    default:
      return false
  }
})

// تحميل القوائم الأساسية
async function loadBaseEmails() {
  emailsLoading.value = true
  try {
    const { data } = await axios.get('/api/emails')
    baseEmails.value = data.map(e => ({
      id: e.id,
      label: e.email,
      value: e.email,
      isNew: false
    }))
    emailOptions.value = [...baseEmails.value]
  } catch (error) {
    console.error('Error loading emails:', error)
  } finally {
    emailsLoading.value = false
  }
}

async function loadBaseCountries() {
  countriesLoading.value = true
  try {
    const { data } = await axios.get('/api/countries')
    baseCountries.value = data.map(c => ({
      id: c.id,
      label: c.name_ar || c.country_name_ar,
      value: c.name_ar || c.country_name_ar,
      isNew: false
    }))
    countryOptions.value = [...baseCountries.value]
  } catch (error) {
    console.error('Error loading countries:', error)
  } finally {
    countriesLoading.value = false
  }
}

// البحث في الإيميلات
function handleEmailSearch(search) {
  if (!search || search.trim() === '') {
    emailOptions.value = [...baseEmails.value]
    return
  }

  const searchLower = search.toLowerCase().trim()
  const filtered = baseEmails.value.filter(email => 
    email.value.toLowerCase().includes(searchLower)
  )

  const exactEmailExists = baseEmails.value.some(email => 
    email.value.toLowerCase() === searchLower
  )

  const alreadyAdded = addedEmails.value.some(email => 
    email.value.toLowerCase() === searchLower
  )

  if (!exactEmailExists && !alreadyAdded && isValidEmail(search.trim())) {
    filtered.push({
      id: 'new_' + Date.now(),
      label: `${search.trim()} (جديد)`,
      value: search.trim(),
      isNew: true
    })
  }

  emailOptions.value = filtered
}

// البحث في الدول
function handleCountrySearch(search) {
  if (!search || search.trim() === '') {
    countryOptions.value = [...baseCountries.value]
    return
  }

  const searchLower = search.toLowerCase().trim()
  const filtered = baseCountries.value.filter(country => 
    country.value.toLowerCase().includes(searchLower)
  )

  const exactCountryExists = baseCountries.value.some(country => 
    country.value.toLowerCase() === searchLower
  )

  const alreadyAdded = selectedCountries.value.some(country => 
    country.value.toLowerCase() === searchLower
  )

  if (!exactCountryExists && !alreadyAdded && search.trim()) {
    filtered.push({
      id: 'new_' + Date.now(),
      label: `${search.trim()} (جديد)`,
      value: search.trim(),
      isNew: true
    })
  }

  countryOptions.value = filtered
}

// إضافة إيميل جديد من الـ tag
function addNewEmailFromTag(newEmail) {
  const trimmedEmail = newEmail.trim()
  
  if (!isValidEmail(trimmedEmail)) {
    Swal.fire('خطأ', 'البريد الإلكتروني غير صالح', 'error')
    return
  }

  const emailExists = addedEmails.value.some(email => 
    email.value.toLowerCase() === trimmedEmail.toLowerCase()
  )
  
  if (emailExists) {
    Swal.fire('تنبيه', 'هذا البريد مضاف بالفعل', 'warning')
    return
  }

  const emailObj = {
    id: 'new_' + Date.now(),
    label: `${trimmedEmail} (جديد)`,
    value: trimmedEmail,
    isNew: true
  }

  addedEmails.value.push(emailObj)
  selectedEmailInput.value = null
  emailOptions.value = [...baseEmails.value]
}

// إضافة دولة جديدة من الـ tag
function addNewCountryFromTag(newCountry) {
  const trimmedCountry = newCountry.trim()
  
  if (!trimmedCountry) return

  const countryExists = selectedCountries.value.some(country => 
    country.value.toLowerCase() === trimmedCountry.toLowerCase()
  )
  
  if (countryExists) {
    Swal.fire('تنبيه', 'هذه الدولة مضافة بالفعل', 'warning')
    return
  }

  const countryObj = {
    id: 'new_' + Date.now(),
    label: `${trimmedCountry} (جديد)`,
    value: trimmedCountry,
    isNew: true
  }

  selectedCountries.value.push(countryObj)
  selectedCountryInput.value = null
  countryOptions.value = [...baseCountries.value]
}

// مراقبة تغيير الطبيب
watch(selectedDoctor, async doc => {
  if (!doc?.type) return

  try {
    const { data: emailPricing } = await axios.get('/api/pricings', {
      params: { type: 'email', doctor_type: doc.type }
    })
    unitPrice.value = emailPricing.length ? Number(emailPricing[0].amount) : 0

    const { data: mailPricings } = await axios.get('/api/pricings', {
      params: { type: 'mail', doctor_type: doc.type }
    })
    servicesFull.value = mailPricings.map(p => ({
      id: p.id,
      label: `${p.name} (${Number(p.amount).toFixed(2)} د.ل)`,
      amount: Number(p.amount),
      file: null,
      work_mention: null,
      file_name: p.file_name || 'مرفق',
      file_required: Boolean(p.file_required)
    }))
    filteredServices.value = [...servicesFull.value]
  } catch (error) {
    console.error('Error loading doctor data:', error)
  }
})

// مراقبة الاختيارات
watch(selectedEmailInput, email => {
  if (email && email.value && email.value.trim()) {
    const alreadyExists = addedEmails.value.some(e => 
      e.value.toLowerCase() === email.value.toLowerCase()
    )
    
    if (!alreadyExists) {
      addedEmails.value.push({
        ...email,
        value: email.value.trim()
      })
    }
    
    emailOptions.value = [...baseEmails.value]
  }
  selectedEmailInput.value = null
})

watch(selectedCountryInput, country => {
  if (country && country.value && country.value.trim()) {
    const alreadyExists = selectedCountries.value.some(c => 
      c.value.toLowerCase() === country.value.toLowerCase()
    )
    
    if (!alreadyExists) {
      selectedCountries.value.push({
        ...country,
        value: country.value.trim()
      })
    }
    
    countryOptions.value = [...baseCountries.value]
  }
  selectedCountryInput.value = null
})

watch(selectedService, service => {
  if (service) {
    selectedServices.value.push({ ...service })
  }
  selectedService.value = null
})

// الوظائف المساعدة
function fetchDoctors(search) {
  if (!search) return
  
  doctorsLoading.value = true
  axios.get('/api/doctors', { params: { search } })
    .then(response => {
      doctors.value = response.data.map(d => ({ 
        ...d, 
        label: `${d.name} (${d.code})` 
      }))
    })
    .catch(error => console.error('Error fetching doctors:', error))
    .finally(() => doctorsLoading.value = false)
}

function fetchServices(search) {
  const query = (search || '').toLowerCase()
  filteredServices.value = servicesFull.value.filter(s =>
    s.label.toLowerCase().includes(query)
  )
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

// دالة معالجة تغيير الملف
function onServiceFileChange(event, index) {
  const file = event.target.files[0]
  if (!file) return

  const maxSize = 5 * 1024 * 1024 // 5MB
  if (file.size > maxSize) {
    Swal.fire({
      icon: 'error',
      title: 'حجم الملف كبير جداً',
      text: 'حجم الملف يجب أن يكون أقل من 5 ميجابايت',
      confirmButtonText: 'موافق'
    })
    event.target.value = ''
    return
  }

  const allowedTypes = [
    'application/pdf',
    'image/jpeg', 'image/jpg', 'image/png',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
  ]
  
  if (!allowedTypes.includes(file.type)) {
    Swal.fire({
      icon: 'error',
      title: 'نوع الملف غير مدعوم',
      text: 'يرجى اختيار ملف من النوع: PDF, JPG, PNG, DOC, أو DOCX',
      confirmButtonText: 'موافق'
    })
    event.target.value = ''
    return
  }

  selectedServices.value[index].file = file
}

// دالة إزالة الملف
function removeServiceFile(index) {
  selectedServices.value[index].file = null
  const fileInputs = document.querySelectorAll(`input[type="file"]`)
  if (fileInputs[index]) {
    fileInputs[index].value = ''
  }
}

// إزالة العناصر
function removeService(index) {
  selectedServices.value.splice(index, 1)
}

function removeEmail(index) {
  addedEmails.value.splice(index, 1)
}

function removeCountry(index) {
  selectedCountries.value.splice(index, 1)
}

// الحسابات
const totalEmailAmount = computed(() => unitPrice.value * addedEmails.value.length)
const totalServicesAmount = computed(() => 
  selectedServices.value.reduce((sum, s) => sum + s.amount, 0)
)
const grandTotal = computed(() => totalEmailAmount.value + totalServicesAmount.value)

// حساب ما إذا كان هناك ملفات مطلوبة غير مرفوعة
const hasRequiredFilesNotUploaded = computed(() => {
  return selectedServices.value.some(service => 
    service.file_required && !service.file
  )
})

// شرط إمكانية الإرسال النهائي
const canSubmit = computed(() => {
  return selectedDoctor.value && 
         addedEmails.value.length > 0 && 
         selectedServices.value.length > 0 &&
         selectedServices.value.every(s => 
           ![8,23,36].includes(s.id) || s.work_mention
         ) &&
         selectedServices.value.every(s => 
           !s.file_required || s.file
         )
})

// إرسال النموذج
async function handleSubmit() {
  if (!canSubmit.value) {
    let errorMessage = 'يرجى استكمال جميع الحقول المطلوبة'
    
    if (!selectedDoctor.value) {
      errorMessage = 'يرجى اختيار طبيب'
    } else if (addedEmails.value.length === 0) {
      errorMessage = 'يرجى إضافة بريد إلكتروني واحد على الأقل'
    } else if (selectedServices.value.length === 0) {
      errorMessage = 'يرجى اختيار خدمة واحدة على الأقل'
    } else if (hasRequiredFilesNotUploaded.value) {
      errorMessage = 'يرجى رفع جميع المرفقات المطلوبة'
    } else if (selectedServices.value.some(s => [8,23,36].includes(s.id) && !s.work_mention)) {
      errorMessage = 'يرجى تحديد خيار جهة العمل للخدمات المطلوبة'
    }
    
    Swal.fire('تنبيه', errorMessage, 'warning')
    return
  }

  submitting.value = true
  
  try {
    const formData = new FormData()
    formData.append('doctor_id', selectedDoctor.value.id)
    
    // الإيميلات
    addedEmails.value.forEach((email, index) => {
      formData.append(`emails[${index}][value]`, email.value)
      formData.append(`emails[${index}][is_new]`, email.isNew ? '1' : '0')
    })
    
    // الدول
    selectedCountries.value.forEach((country, index) => {
      formData.append(`countries[${index}][value]`, country.value)
      formData.append(`countries[${index}][is_new]`, country.isNew ? '1' : '0')
    })
    
    // الخدمات مع الملفات
    selectedServices.value.forEach((service, index) => {
      formData.append(`services[${index}][id]`, service.id)
      formData.append(`services[${index}][amount]`, service.amount)
      
      if (service.file) {
        formData.append(`services[${index}][file]`, service.file)
      }
      
      formData.append(`services[${index}][file_name]`, service.file_name || '')
      formData.append(`services[${index}][file_required]`, service.file_required ? '1' : '0')
      
      if (service.work_mention) {
        formData.append(`services[${index}][work_mention]`, service.work_mention)
      }
    })
    
    // معلومات إضافية
    formData.append('notes', notes.value)
    formData.append('extracted_before', extractedBefore.value ? '1' : '0')
    if (extractedBefore.value && lastExtractYear.value) {
      formData.append('last_extract_year', lastExtractYear.value)
    }

    await axios.post('/api/doctor-mails', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    await Swal.fire({
      icon: 'success',
      title: 'تم إرسال الطلب بنجاح',
      text: 'سيتم مراجعة طلبك وإشعارك بالنتيجة',
      timer: 2000,
      showConfirmButton: false
    })

    window.location = props.doctorId
      ? '/doctor/doctor-mails?requests=1'
      : '/admin/doctor-mails'

  } catch (error) {
    console.error('Submission error:', error)
    
    let errorMessage = 'حدث خطأ أثناء إرسال الطلب'
    
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage = error.response.data.message
    } else if (error.response && error.response.data && error.response.data.errors) {
      const errors = error.response.data.errors
      errorMessage = Object.values(errors).flat().join('\n')
    }
    
    Swal.fire('خطأ', errorMessage, 'error')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
/* حل مشكلة z-index للسيليكت */
.vue3-select {
  direction: rtl;
  position: relative;
  z-index: 1;
}

.vue3-select .vs__dropdown-menu {
  z-index: 99999 !important;
  position: absolute;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  border-radius: 8px;
  border: 1px solid #dee2e6;
  background: white;
}

.vue3-select .vs__dropdown-option {
  padding: 12px 16px;
  transition: all 0.2s ease;
  color: #495057;
}

.vue3-select .vs__dropdown-option--highlight {
  background: #007bff !important;
  color: white !important;
}

/* Steps Progress Styles */
.stepped-form-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.steps-progress {
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.step-progress-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.step-progress-bar::before {
  content: '';
  position: absolute;
  top: 25px;
  left: 10%;
  right: 10%;
  height: 3px;
  background: #e9ecef;
  z-index: 1;
}

.step-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  z-index: 2;
  transition: all 0.3s ease;
}

.step-circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #e9ecef;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
  border: 3px solid transparent;
}

.step-label {
  font-size: 12px;
  color: #6c757d;
  text-align: center;
  font-weight: 500;
  transition: all 0.3s ease;
}

.step-item.active .step-circle {
  background: #007bff;
  color: white;
  border-color: #0056b3;
  box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.2);
  transform: scale(1.1);
}

.step-item.active .step-label {
  color: #007bff;
  font-weight: 600;
}

.step-item.completed .step-circle {
  background: #28a745;
  color: white;
  border-color: #1e7e34;
}

.step-item.completed .step-label {
  color: #28a745;
  font-weight: 600;
}

.step-item.disabled .step-circle {
  background: #f8f9fa;
  color: #adb5bd;
}

.step-item.disabled .step-label {
  color: #adb5bd;
}

/* Step Content Styles */
.step-content {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Navigation Styles */
.step-navigation {
  padding: 25px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-top: 30px;
}

.step-info {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Summary Section Styles */
.summary-section {
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
  border-left: 4px solid #007bff;
}

.summary-section h6 {
  color: #007bff;
  font-weight: 600;
  margin-bottom: 10px;
}

/* Alert Styles */
.alert {
  border: none;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  position: relative;
  z-index: 1;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  font-weight: 500;
}

.alert-info {
  background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
  color: #0d47a1;
  border-left: 5px solid #2196f3;
  box-shadow: 0 4px 12px rgba(33, 150, 243, 0.15);
}

.alert-success {
  background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
  color: #1b5e20;
  border-left: 5px solid #4caf50;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
}

.alert-warning {
  background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
  color: #e65100;
  border-left: 5px solid #ff9800;
  box-shadow: 0 4px 12px rgba(255, 152, 0, 0.15);
}

.alert-danger {
  background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
  color: #b71c1c;
  border-left: 5px solid #f44336;
  box-shadow: 0 4px 12px rgba(244, 67, 54, 0.15);
}

/* Card Styles */
.card {
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  border: 1px solid #e9ecef;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

.card-header {
  font-weight: 600;
  border-radius: 15px 15px 0 0;
  padding: 20px 25px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.card-body {
  padding: 25px;
}

/* Button Styles */
.btn {
  border-radius: 10px;
  font-weight: 500;
  transition: all 0.3s ease;
  padding: 12px 20px;
}

.btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border: none;
}

.btn-success {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
  border: none;
}

.btn-outline-secondary {
  border: 2px solid #6c757d;
  color: #6c757d;
}

.btn-outline-secondary:hover {
  background: #6c757d;
  color: white;
}

/* Form Controls */
.form-control {
  border-radius: 8px;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
  padding: 12px 15px;
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control.is-invalid {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-control.is-valid {
  border-color: #28a745;
  box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Doctor Info Display */
.doctor-info {
  border: 2px solid #007bff;
  background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
}

.doctor-info h5 {
  color: #007bff;
  margin-bottom: 5px;
}

/* Badges */
.badge {
  font-size: 0.75rem;
  padding: 0.5em 0.75em;
  border-radius: 6px;
  font-weight: 500;
}

.badge.bg-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.badge.bg-light {
  background: #f8f9fa !important;
  color: #495057 !important;
}

/* Required field indicator */
.required::after {
  content: ' *';
  color: #dc3545;
  font-weight: 600;
}

/* File upload styles */
.text-success {
  color: #28a745 !important;
  font-weight: 500;
}

.btn-outline-danger {
  --bs-btn-padding-x: 0.5rem;
  --bs-btn-padding-y: 0.25rem;
  --bs-btn-font-size: 0.75rem;
}

/* Loading states */
.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.fa-spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
  .stepped-form-container {
    padding: 15px;
  }
  
  .steps-progress {
    padding: 20px 15px;
  }
  
  .step-progress-bar {
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .step-progress-bar::before {
    display: none;
  }
  
  .step-circle {
    width: 40px;
    height: 40px;
    font-size: 14px;
  }
  
  .step-label {
    font-size: 11px;
  }
  
  .card-body {
    padding: 20px;
  }
  
  .step-navigation {
    padding: 20px 15px;
  }
  
  .step-navigation .btn {
    padding: 10px 15px;
    font-size: 14px;
  }
  
  .step-info {
    order: 3;
    width: 100%;
    margin-top: 15px;
  }
}

@media (max-width: 576px) {
  .step-navigation {
    flex-direction: column;
    gap: 15px;
  }
  
  .step-navigation .btn {
    width: 100%;
  }
}
</style>