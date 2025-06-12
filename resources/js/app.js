import { createApp } from 'vue'
import doctorRequest from './components/DoctorRequest.vue'
import doctorRequestEdit from './components/DoctorRequestEdit.vue'

const app = createApp({})
app.component('doctor-request', doctorRequest)
app.component('doctor-request-edit', doctorRequestEdit)
app.mount('#app')

