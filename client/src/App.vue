<script setup>
import { RouterLink, RouterView } from 'vue-router'
import useAuth from './composables/useAuth';

const { user, logout } = useAuth();
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <RouterLink class="navbar-brand" to="/">AuthApp</RouterLink>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <RouterLink class="nav-link" active-class="active" to="/">Home</RouterLink>
          </li>
          <li class="nav-item" v-if="user">
            <RouterLink class="nav-link" active-class="active" to="/dashboard">Dashboard</RouterLink>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <template v-if="!user">
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active" to="/login">Login</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active" to="/register">Registrazione</RouterLink>
            </li>
          </template>
          <template v-else>
            <li class="nav-item">
              <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container mt-4">
    <RouterView />
  </main>
</template>

<style>
body {
  background-color: #f8f9fa;
}
</style>