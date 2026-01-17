<script setup lang="ts">
import {ref, reactive} from 'vue';
import {useRouter} from 'vue-router';
import api from '../api';
import InputField from '../components/InputField.vue';
import PrimaryButton from '../components/PrimaryButton.vue';
import {useValidation} from '../composables/useValidation';

const router = useRouter();
const {validateEmail, validatePassword} = useValidation();

const form = reactive({
  email: '',
  password: ''
});

const errors = ref<Record<string, string>>({});
const isLoading = ref(false);

const validate = () => {
  errors.value = {};

  const emailError = validateEmail(form.email);
  if (emailError) errors.value.email = emailError;

  const passwordError = validatePassword(form.password);
  if (passwordError) errors.value.password = passwordError;

  return Object.keys(errors.value).length === 0;
};

const handleLogin = async () => {
  if (!validate()) return;

  isLoading.value = true;
  try {
    // Assuming backend endpoint is /login or similar, usually getting a token back
    await api.post('/login', form);

    // Redirect to home on success
    router.push({name: 'home'});
  } catch (error: any) {
    if (error.response?.data?.errors) {
      const backendErrors = error.response.data.errors;
      Object.keys(backendErrors).forEach(key => {
        errors.value[key] = backendErrors[key][0];
      });
    } else if (error.response?.data?.message) {
      // Often login just returns a "Invalid credentials" message
      alert(error.response.data.message);
    } else {
      alert('Login failed. Please try again.');
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
          Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Or
          <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
            create a new account
          </router-link>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
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
        </div>

        <div>
          <PrimaryButton type="submit" :loading="isLoading">
            Sign in
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
