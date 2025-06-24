<script setup>
import { ref } from 'vue';
import useAuth from '../composables/useAuth';

const { forgotPassword, isLoading } = useAuth();
const email = ref('');

const handleForgotPassword = async () => {
    await forgotPassword(email.value);
};
</script>

<template>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
        <div class="card-body">
        <h1 class="card-title text-center mb-4">Password Dimenticata</h1>
        <p class="text-muted text-center">Inserisci la tua email e ti invieremo un link per reimpostare la password.</p>
        <form @submit.prevent="handleForgotPassword">
            <div class="mb-3">
            <label for="email" class="form-label">Indirizzo Email</label>
            <input type="email" class="form-control" id="email" v-model="email" required :disabled="isLoading">
            </div>

            <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
            <span v-if="isLoading" class="spinner-border spinner-border-sm"></span>
            <span v-else>Invia Link di Reset</span>
            </button>
        </form>
        </div>
    </div>
    </div>
</div>
</template>