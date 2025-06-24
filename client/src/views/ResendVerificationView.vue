<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import useAuth from '../composables/useAuth';

const { publicResendVerificationEmail, isLoading } = useAuth();

// Schema di validazione con VeeValidate e Yup
const schema = yup.object({
  email: yup.string().required("L'email è obbligatoria").email("Inserisci un'email valida"),
});

// La funzione di submit che verrà chiamata solo se la validazione frontend passa
const handleResend = async (values) => {
  await publicResendVerificationEmail(values.email);
};
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="card-title text-center mb-4">Invia di Nuovo l'Email di Verifica</h1>
          <p class="text-muted text-center">Non hai ricevuto l'email? Inserisci il tuo indirizzo qui sotto per riceverne una nuova.</p>
          <Form @submit="handleResend" :validation-schema="schema" v-slot="{ errors }">
            <div class="mb-3">
              <label for="email" class="form-label">Il Tuo Indirizzo Email</label>
              <Field name="email" type="email" class="form-control" :class="{'is-invalid': errors.email}" id="email" required :disabled="isLoading" />
              <ErrorMessage name="email" class="text-danger small" />
            </div>
            
            <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
              <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
              <span v-else>Invia di Nuovo il Link</span>
            </button>
          </Form>
        </div>
      </div>
    </div>
  </div>
</template>