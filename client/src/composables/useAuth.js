import { ref, readonly } from 'vue'
import axios from '../axios'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

const user = ref(null)
const isAuthCheckCompleted = ref(false)
const isLoading = ref(false)

export default function useAuth() {
  const toast = useToast()
  const router = useRouter()

  const login = async (credentials) => {
    isLoading.value = true
    try {
      const response = await axios.post('/api/login', credentials)
      user.value = response.data
      await router.push('/dashboard')
      toast.success('Login effettuato con successo!')
    } catch (error) {
      toast.error('Le credenziali fornite non sono corrette.')
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const register = async (userInfo) => {
    isLoading.value = true
    try {
      await axios.post('/api/register', userInfo)
      toast.success("Registrazione completata! Controlla la tua email per attivare l'account.")
    } catch (error) {
      if (error.response && error.response.status === 422) {
        const errors = error.response.data.errors
        const firstErrorKey = Object.keys(errors)[0]
        toast.error(errors[firstErrorKey][0])
      } else {
        toast.error('Si è verificato un errore durante la registrazione.')
      }
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const resendVerificationEmail = async () => {
    isLoading.value = true
    try {
      const response = await axios.post('/api/email/verification-notification')
      toast.success(response.data.message)
    } catch (error) {
      toast.error(error.response?.data?.message || 'Errore durante il reinvio.')
    } finally {
      isLoading.value = false
    }
  }

  const publicResendVerificationEmail = async (email) => {
    isLoading.value = true
    try {
      const response = await axios.post('/api/resend-verification-email', { email })
      toast.success(response.data.message)
    } catch (error) {
      toast.error('Impossibile completare la richiesta. Riprova più tardi.')
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    isLoading.value = true
    try {
      await axios.post('/api/logout')
      user.value = null
      await router.push('/login')
      toast.info('Logout effettuato. A presto!')
    } finally {
      isLoading.value = false
    }
  }

  const getUser = async () => {
    try {
      const response = await axios.get('/api/user')
      user.value = response.data
    } catch (error) {
      user.value = null
    } finally {
      isAuthCheckCompleted.value = true
    }
  }

  const forgotPassword = async (email) => {
    isLoading.value = true
    try {
      const response = await axios.post('/api/forgot-password', { email })
      toast.success(response.data.message)
    } catch (error) {
      toast.error(error.response?.data?.message || 'Si è verificato un errore.')
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const resetPassword = async (formData) => {
    isLoading.value = true
    try {
      const response = await axios.post('/api/reset-password', formData)
      toast.success(response.data.message)
      await router.push('/login')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Si è verificato un errore.')
      throw error
    } finally {
      isLoading.value = false
    }
  }

  return {
    user: readonly(user),
    isAuthCheckCompleted: readonly(isAuthCheckCompleted),
    isLoading: readonly(isLoading),
    login,
    register,
    resendVerificationEmail,
    publicResendVerificationEmail,
    logout,
    getUser,
    forgotPassword,
    resetPassword,
  }
}
