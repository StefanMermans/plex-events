<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import InputField from '../components/InputField.vue';
import PrimaryButton from '../components/PrimaryButton.vue';
import { useValidation } from '../composables/useValidation';

const router = useRouter();
const { validateEmail, validatePassword, validatePasswordConfirmation } = useValidation();

const form = reactive({
  email: '',
  password: '',
  passwordConfirmation: ''
});

const errors = ref<Record<string, string>>({});
const isLoading = ref(false);

const validate = () => {
  errors.value = {};
  
  const emailError = validateEmail(form.email);
  if (emailError) errors.value.email = emailError;
  
  const passwordError = validatePassword(form.password);
  if (passwordError) errors.value.password = passwordError;
  
  const confirmationError = validatePasswordConfirmation(form.password, form.passwordConfirmation);
  if (confirmationError) errors.value.passwordConfirmation = confirmationError;

  return Object.keys(errors.value).length === 0;
};

const handleRegister = async () => {
  if (!validate()) return;

  isLoading.value = true;
  try {
    await api.post('/register', form);
    // Assuming successful registration logs the user in or redirects to login
    // For now, let's redirect to home
    router.push({name: 'home'});
  } catch (error: any) {
    if (error.response?.data?.errors) {
      // Map backend validation errors (assuming Laravel format)
      // Laravel sends { errors: { field: ['Error message'] } }
      const backendErrors = error.response.data.errors;
      Object.keys(backendErrors).forEach(key => {
        errors.value[key] = backendErrors[key][0];
      });
    } else {
       // Generic error
       alert('Registration failed. Please try again.');
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 dark:bg-gray-900 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
          Create an account
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Or
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
            sign in to your existing account
          </router-link>
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="space-y-4 rounded-md shadow-sm">
          <InputField
            id="email"
            label="Email address"
            v-model="form.email"
            type="email"
            placeholder="you@example.com"
            :error="errors.email"
          />
          
          <InputField
            id="password"
            label="Password"
            v-model="form.password"
            type="password"
            placeholder="••••••••"
            :error="errors.password"
          />
          
          <InputField
            id="passwordConfirmation"
            label="Confirm Password"
            v-model="form.passwordConfirmation"
            type="password"
            placeholder="••••••••"
            :error="errors.passwordConfirmation"
          />
        </div>

        <div>
          <PrimaryButton type="submit" :loading="isLoading">
            Sign up
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
