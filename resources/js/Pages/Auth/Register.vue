<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-900/5 via-teal-800/5 to-emerald-900/5">
            <div class="w-full max-w-5xl bg-white/80 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row border border-teal-500/20">
 
                <!-- Left: Image with teal overlay -->
                <div class="hidden md:block md:w-1/2 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-600/40 to-emerald-600/40 mix-blend-multiply"></div>
                    <img
                        src="/assets/back-3.png"
                        alt="Register Visual"
                        class="h-full w-full object-cover transform hover:scale-105 transition-transform duration-700"
                    />
                    <!-- Decorative overlay -->
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
                            <p class="text-white text-sm font-medium">Join Our Community</p>
                            <p class="text-teal-200 text-xs mt-1">Start your journey with ALINASWE FARM</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 overflow-y-auto max-h-[90vh]">
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
                            <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">Create Account</span>
                        </h2>
                        <p class="text-gray-600 text-sm">
                            Join the Alinaswe Farm community and start your journey with us today!
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Name Field -->
                        <div>
                            <InputLabel for="name" value="Full Name" class="text-sm font-semibold text-gray-700 mb-1 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <TextInput 
                                    id="name" 
                                    type="text"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 p-3 focus:border-teal-500 focus:ring focus:ring-teal-500/20 focus:ring-opacity-50 transition-all duration-200" 
                                    placeholder="Enter your full name"
                                    v-model="form.name" 
                                    required 
                                    autofocus 
                                    autocomplete="name" 
                                />
                            </div>
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.name" />
                        </div>

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
                                    placeholder="Create a strong password" 
                                    v-model="form.password" 
                                    required
                                    autocomplete="new-password" 
                                />
                            </div>
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password" class="text-sm font-semibold text-gray-700 mb-1 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <TextInput 
                                    id="password_confirmation" 
                                    type="password"
                                    class="mt-1 block w-full pl-10 rounded-lg border border-gray-300 p-3 focus:border-teal-500 focus:ring focus:ring-teal-500/20 focus:ring-opacity-50 transition-all duration-200"
                                    placeholder="Confirm your password" 
                                    v-model="form.password_confirmation" 
                                    required
                                    autocomplete="new-password" 
                                />
                            </div>
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Password Requirements -->
                        <div class="bg-gray-50 rounded-lg p-3 space-y-1">
                            <p class="text-xs font-semibold text-gray-700 mb-1">Password Requirements:</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="flex items-center gap-1 text-gray-600">
                                    <svg class="w-3 h-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    At least 8 characters
                                </span>
                                <span class="flex items-center gap-1 text-gray-600">
                                    <svg class="w-3 h-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Uppercase & lowercase
                                </span>
                                <span class="flex items-center gap-1 text-gray-600">
                                    <svg class="w-3 h-3 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Contains numbers
                                </span>
                            </div>
                        </div>

                        <!-- Register Button -->
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
                            <span v-else>Create Account</span>
                            <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Sign In Link -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-center text-gray-600">
                            Already have an account?
                            <Link 
                                href="/login" 
                                class="text-teal-600 hover:text-emerald-600 font-semibold ml-1 transition-colors duration-200"
                            >
                                Sign in here
                            </Link>
                        </p>
                    </div>

                    <!-- Terms and Privacy -->
                    <!-- <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500">
                            By creating an account, you agree to our 
                            <a href="#" class="text-teal-600 hover:text-teal-700">Terms of Service</a> 
                            and 
                            <a href="#" class="text-teal-600 hover:text-teal-700">Privacy Policy</a>
                        </p>
                    </div> -->
                </div>

            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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

/* Custom scrollbar for form container */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(20, 184, 166, 0.3);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(20, 184, 166, 0.5);
}
</style>


<!-- <script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="min-h-screen flex items-center justify-center">
            <div class="w-full h-screen bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

             
                <div class="hidden md:block md:w-1/2 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#0078D4]/30 to-[#00BCF2]/30 mix-blend-overlay"></div>
                    <img src="/assets/one.png" alt="Register Visual" class="h-full w-full object-cover" />
                </div>

         
                <div class="w-full md:w-1/2 p-8 md:p-16 overflow-y-auto">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold mb-2 flex justify-center items-center gap-2">
                            <span class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent">My AAIB</span>
                        </h2>
                        <p class="text-gray-900">
                            Create your account
                            <span class="font-semibold bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent">Sign up</span>
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="name" value="Name" class="text-sm font-medium text-gray-700" />
                            <TextInput 
                                id="name" 
                                type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-[#0078D4] focus:ring focus:ring-[#0078D4]/20 focus:ring-opacity-50" 
                                placeholder="Enter full name"
                                v-model="form.name" 
                                required 
                                autofocus 
                                autocomplete="name" 
                            />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" class="text-sm font-medium text-gray-700" />
                            <TextInput 
                                id="email" 
                                type="email"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-[#0078D4] focus:ring focus:ring-[#0078D4]/20 focus:ring-opacity-50"
                                placeholder="Enter email" 
                                v-model="form.email" 
                                required 
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
                                autocomplete="new-password" 
                            />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password"
                                class="text-sm font-medium text-gray-700" />
                            <TextInput 
                                id="password_confirmation" 
                                type="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 focus:border-[#0078D4] focus:ring focus:ring-[#0078D4]/20 focus:ring-opacity-50"
                                placeholder="Repeat password" 
                                v-model="form.password_confirmation" 
                                required
                                autocomplete="new-password" 
                            />
                            <InputError class="mt-1 text-red-500 text-sm"
                                :message="form.errors.password_confirmation" />
                        </div>

                        <PrimaryButton
                            class="w-full bg-gradient-to-r from-[#0078D4] to-[#00BCF2] hover:from-[#00BCF2] hover:to-[#0078D4] text-white py-2 rounded-md flex justify-center items-center transition-all duration-300 shadow-lg hover:shadow-[#0078D4]/30"
                            :class="{ 'opacity-25': form.processing }" 
                            :disabled="form.processing"
                        >
                            Register
                        </PrimaryButton>
                        
               
                        <div class="text-xs text-gray-500 mt-2">
                            <p class="flex items-center gap-1">
                                <span class="text-[#0078D4]">•</span> Password must be at least 8 characters
                            </p>
                        </div>
                    </form>

                    <p class="mt-6 text-sm text-center text-gray-600">
                        Already have an account?
                        <Link 
                            href="/login" 
                            class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent font-medium hover:from-[#00BCF2] hover:to-[#0078D4] transition-all duration-300"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>

            </div>
        </div>
    </GuestLayout>
</template> -->









<!-- <script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <div class="min-h-screen flex items-center justify-center bg-white">
            <div class="w-full h-screen bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

              
                <div class="hidden md:block md:w-1/2">
                    <img src="/assets/one.png" alt="Register Visual" class="h-full w-full object-cover" />
                </div>

                
                <div class="w-full md:w-1/2 p-8 md:p-16">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex justify-center items-center gap-2">
                            MYQR
                        </h2>
                        <p class="text-gray-900">
                            Create your account <br />
                            Sign up
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="name" value="Name" class="text-sm font-medium text-gray-700" />
                            <TextInput id="name" type="text"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2" placeholder="Enter full name"
                                v-model="form.name" required autofocus autocomplete="name" />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" class="text-sm font-medium text-gray-700" />
                            <TextInput id="email" type="email"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2"
                                placeholder="Enter email" v-model="form.email" required autocomplete="username" />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700" />
                            <TextInput id="password" type="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2"
                                placeholder="At least 8 characters" v-model="form.password" required
                                autocomplete="new-password" />
                            <InputError class="mt-1 text-red-500 text-sm" :message="form.errors.password" />
                        </div>

                        <div>
                            <InputLabel for="password_confirmation" value="Confirm Password"
                                class="text-sm font-medium text-gray-700" />
                            <TextInput id="password_confirmation" type="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2"
                                placeholder="Repeat password" v-model="form.password_confirmation" required
                                autocomplete="new-password" />
                            <InputError class="mt-1 text-red-500 text-sm"
                                :message="form.errors.password_confirmation" />
                        </div>

                        <PrimaryButton
                            class="w-full bg-purple-900 hover:bg-purple-800 text-white py-2 rounded-md flex justify-center items-center"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Register
                        </PrimaryButton>
                    </form>

                    <p class="mt-6 text-sm text-center text-gray-600">
                        Already have an account?
                        <Link href="/login" class="text-purple-900 font-medium hover:underline">Sign in</Link>
                    </p>
                </div>

            </div>
        </div>
    </GuestLayout>
</template> -->