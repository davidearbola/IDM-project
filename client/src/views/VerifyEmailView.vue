<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';

const route = useRoute();
const toast = useToast();

const status = ref('Verifica in corso... Reindirizzamento al server per la conferma.');
const hasError = ref(false);

onMounted(() => {
  // 1. Prendiamo l'URL di verifica del backend dal nostro parametro
  const verifyUrl = route.query.verify_url;

  if (!verifyUrl) {
    status.value = 'URL di verifica non valido o mancante.';
    hasError.value = true;
    toast.error(status.value);
    return;
  }
  
  // 2. LA SOLUZIONE: Invece di usare Axios, facciamo un reindirizzamento
  //    nativo del browser. Questo permette al backend di gestire correttamente
  //    la richiesta firmata. Sarà poi il backend a reindirizzarci indietro
  //    alla pagina di login con il messaggio di successo.
  window.location.href = verifyUrl;

});
</script>

<template>
    <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h3 class="mt-3">
                {{ status }}
            </h3>
            <p class="text-muted">Se non vieni reindirizzato, per favore controlla il link che hai ricevuto via email.</p>
        </div>
    </div>
</template>