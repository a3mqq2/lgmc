import { createApp } from 'vue'
import doctorRequest from './components/DoctorRequest.vue'

const app = createApp({})
app.component('doctor-request', doctorRequest)
app.mount('#app')

