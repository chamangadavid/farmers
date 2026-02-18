<script setup>
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

                <!-- Left: Image with blue overlay -->
                <div class="hidden md:block md:w-1/2 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#0078D4]/30 to-[#00BCF2]/30 mix-blend-overlay"></div>
                    <img src="/assets/one.png" alt="Register Visual" class="h-full w-full object-cover" />
                </div>

                <!-- Right: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-16 overflow-y-auto">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold mb-2 flex justify-center items-center gap-2">
                            <span class="bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent">MyQR CODE</span>
                        </h2>
                        <p class="text-gray-900">
                            Create your account <br />
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
                        
                        <!-- Password Requirements Hint -->
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
</template>

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