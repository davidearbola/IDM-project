<script setup>
import { ref } from 'vue'; 
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import useAuth from '../composables/useAuth';

const { register, isLoading } = useAuth();
const errorMessage = ref(''); 

const schema = yup.object({
  name: yup.string().required('Il nome è obbligatorio'),
  email: yup.string().required('L\'email è obbligatoria').email('Inserisci un\'email valida'),
  password: yup.string().required('La password è obbligatoria').min(8, 'La password deve contenere almeno 8 caratteri'),
  password_confirmation: yup.string()
    .required('La conferma della password è obbligatoria')
    .oneOf([yup.ref('password')], 'Le password non corrispondono'),
});

const handleRegister = async (values) => {
  errorMessage.value = '';
  try {
    await register(values);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errorMessage.value = error.response.data.errors[Object.keys(error.response.data.errors)[0]][0];
    } else {
      errorMessage.value = 'Si è verificato un errore durante la registrazione.';
    }
  }
};
</script>

<template>
    <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
        <div class="card-body">
        <h1 class="card-title text-center mb-4">Crea un Account</h1>

        <Form @submit="handleRegister" :validation-schema="schema" v-slot="{ errors }">
            <div class="mb-3">
              <label for="name" class="form-label">Nome</label>
              <Field name="name" type="text" class="form-control" :class="{'is-invalid': errors.name}" id="name" required :disabled="isLoading" />
              <ErrorMessage name="name" class="text-danger small" />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Indirizzo Email</label>
              <Field name="email" type="email" class="form-control" :class="{'is-invalid': errors.email}" id="email" required :disabled="isLoading" />
              <ErrorMessage name="email" class="text-danger small" />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <Field name="password" type="password" class="form-control" :class="{'is-invalid': errors.password}" id="password" required :disabled="isLoading" />
              <ErrorMessage name="password" class="text-danger small" />
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Conferma Password</label>
              <Field name="password_confirmation" type="password" class="form-control" :class="{'is-invalid': errors.password_confirmation}" id="password_confirmation" required :disabled="isLoading" />
              <ErrorMessage name="password_confirmation" class="text-danger small" />
            </div>

            <div v-if="errorMessage" class="alert alert-danger" role="alert">
              {{ errorMessage }}
            </div>

            <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
              <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
              <span v-else>Registrati</span>
            </button>
        </Form>
        </div>
    </div>
    </div>
</div>
</template>