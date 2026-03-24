<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-900/5 via-teal-800/5 to-emerald-900/5">
            <div class="w-full max-w-5xl bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row border border-teal-500/20">
 
                <!-- Left: Image with teal overlay -->
                <div class="hidden md:block md:w-1/2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-600/40 to-emerald-600/40 mix-blend-multiply"></div>
                    <img
                        src="/assets/login.png"
                        alt="Login Visual"
                        class="h-full w-full object-cover transform hover:scale-105 transition-transform duration-700"
                    />
                    <!-- Decorative overlay -->
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
                            <p class="text-white text-sm font-medium">Aircraft Accident Investigation Board</p>
                            <p class="text-teal-200 text-xs mt-1">Safety Through Excellence</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16">
                    <!-- Logo and Title -->
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <div class="relative">
                                <img
                                    src="/assets/aaib.png"
                                    alt="AAIB"
                                    class="h-16 w-auto"
                                />
                                <div class="absolute -inset-2 bg-gradient-to-r from-teal-500 to-emerald-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">
                            <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">Welcome Back</span>
                        </h2>
                        <p class="text-gray-600 text-sm">
                            Sign in to your account to continue
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email Field -->
                        <div>
                            <InputLabel for="email" value="Email Address" class="text-sm font-semibold text-gray-700 mb-1 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 p-3 focus:border-teal-500 focus:ring focus:ring-teal-500/20 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="example@email.com"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                            </div>
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.email" />
                        </div>

                        <!-- Password Field -->
                        <div>
                            <InputLabel for="password" value="Password" class="text-sm font-semibold text-gray-700 mb-1 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V8a4 4 0 00-8 0v3h8z"></path>
                                    </svg>
                                </div>
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 p-3 focus:border-teal-500 focus:ring focus:ring-teal-500/20 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="Enter your password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me and Forgot Password -->
                        <div class="flex justify-between items-center">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <Checkbox 
                                    name="remember" 
                                    v-model:checked="form.remember" 
                                    class="text-teal-600 focus:ring-teal-500 rounded border-gray-300"
                                />
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-teal-600 hover:text-emerald-600 font-medium transition-colors duration-200"
                            >
                                Forgot Password?
                            </Link>
                        </div>
                        
                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold flex justify-center items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-teal-500/30 transform hover:-translate-y-0.5"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else>Sign In</span>
                            <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-center text-gray-600">
                            Don't have an account?
                            <Link href="/register" class="text-teal-600 hover:text-emerald-600 font-semibold ml-1 transition-colors duration-200">
                                Create Account
                            </Link>
                        </p>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500">
                            By signing in, you agree to our 
                            <a href="#" class="text-teal-600 hover:text-teal-700">Terms</a> 
                            and 
                            <a href="#" class="text-teal-600 hover:text-teal-700">Privacy Policy</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
/* Smooth animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

form {
    animation: fadeIn 0.6s ease-out;
}

/* Custom focus styles */
input:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Loading animation for button */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>

<!-- <script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="min-h-screen flex items-center justify-center ">
            <div class="w-full max-w-5xl bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">
 
              
                <div class="hidden md:block md:w-1/2 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#0078D4]/30 to-[#00BCF2]/30 mix-blend-overlay"></div>
                    <img
                        src="/assets/one.png"
                        alt="Login Visual"
                        class="h-full w-full object-cover"
                    />
                </div>

                
                <div class="w-full md:w-1/2 p-8 md:p-16">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold mb-2 flex justify-center items-center gap-2">
                            <span class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent">MY AAIB </span>
                        </h2>
                        <p class="text-gray-900">
                          Already Registered 
                          <span class="font-semibold bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent">Sign in</span>
                        </p>
                      </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="email" value="Email" class="text-sm font-medium text-gray-700" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-[#0078D4] focus:ring focus:ring-[#0078D4]/20 focus:ring-opacity-50"
                                placeholder="Example@email.com"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-[#0078D4] focus:ring focus:ring-[#0078D4]/20 focus:ring-opacity-50"
                                placeholder="At least 8 characters"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center gap-2">
                                <Checkbox name="remember" v-model:checked="form.remember" class="text-[#0078D4] focus:ring-[#0078D4]/20" />
                                <span class="text-gray-600">Remember me</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent font-semibold hover:from-[#00BCF2] hover:to-[#0078D4] transition-all duration-300"
                            >
                                Forgot Password?
                            </Link>
                        </div>
                        
                        <PrimaryButton
                            class="w-full bg-gradient-to-r from-[#0078D4] to-[#00BCF2] hover:from-[#00BCF2] hover:to-[#0078D4] text-white py-2 rounded-md flex justify-center items-center transition-all duration-300 shadow-lg hover:shadow-[#0078D4]/30"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Sign in
                        </PrimaryButton>
                    </form>

                    <p class="mt-6 text-sm text-center text-gray-600">
                        Don't you have an account? 
                        <Link href="/register" class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent font-medium hover:from-[#00BCF2] hover:to-[#0078D4] transition-all duration-300">
                            Sign up
                        </Link>
                    </p>
                </div>

            </div>
        </div>
    </GuestLayout>
</template> -->







<!-- 
<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>  -->
