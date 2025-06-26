<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import useAuth from '../composables/useAuth'
import { useToast } from 'vue-toastification'

const { login, isLoading } = useAuth()
const toast = useToast()
const isNotVerified = ref(false)

const schema = yup.object({
  email: yup.string().required("L'email è obbligatoria").email("Inserisci un'email valida"),
  password: yup.string().required('La password è obbligatoria'),
})

const handleLogin = async (values) => {
  isNotVerified.value = false
  try {
    await login(values)
  } catch (error) {
    if (error.response && error.response.status === 403) {
      isNotVerified.value = true
    }
  }
}

</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Login</h1>
          <Form @submit="handleLogin" :validation-schema="schema" v-slot="{ errors }">
            <div class="mb-3">
              <label for="email" class="form-label">Indirizzo Email</label>
              <Field
                name="email"
                type="email"
                class="form-control"
                :class="{ 'is-invalid': errors.email }"
                id="email"
                required
                :disabled="isLoading"
              />
              <ErrorMessage name="email" class="text-danger small" />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <Field
                name="password"
                type="password"
                class="form-control"
                :class="{ 'is-invalid': errors.password }"
                id="password"
                required
                :disabled="isLoading"
              />
              <ErrorMessage name="password" class="text-danger small" />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <Field
                  name="remember"
                  class="form-check-input"
                  type="checkbox"
                  :value="true"
                  id="remember"
                />
                <label class="form-check-label" for="remember"> Ricordami </label>
              </div>
              <RouterLink to="/forgot-password">Password dimenticata?</RouterLink>
            </div>

            <div v-if="isNotVerified" class="alert alert-warning" role="alert">
              La tua email non è stata verificata. Controlla la tua casella di posta.
              <RouterLink to="/resend-verification" class="alert-link ms-1"
                >Invia di nuovo</RouterLink
              >
            </div>

            <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
              <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
              <span v-else>Accedi</span>
            </button>
          </Form>
        </div>
      </div>
    </div>
  </div>
</template>
