<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:data']);

const localData = ref({
    firstName: props.data?.firstName || '',
    lastName: props.data?.lastName || '',
    organization: props.data?.organization || '',
    title: props.data?.title || '',
    email: props.data?.email || '',
    phoneWork: props.data?.phoneWork || '',
    phoneMobile: props.data?.phoneMobile || '',
    phoneHome: props.data?.phoneHome || '',
    fax: props.data?.fax || '',
    addressStreet: props.data?.addressStreet || '',
    addressCity: props.data?.addressCity || '',
    addressState: props.data?.addressState || '',
    addressZip: props.data?.addressZip || '',
    addressCountry: props.data?.addressCountry || '',
    website: props.data?.website || '',
    birthday: props.data?.birthday || '',
    note: props.data?.note || '',
    socialProfiles: props.data?.socialProfiles || []
});


const addSocialProfile = () => {
    localData.value.socialProfiles.push({
        type: 'linkedin',
        url: ''
    });
};

const removeSocialProfile = (index) => {
    localData.value.socialProfiles.splice(index, 1);
};

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });
</script>

<template>
    <div class="space-y-6">
        <h3 class="font-semibold text-lg">Contact Information (vCard)</h3>

        <!-- Basic Information -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input v-model="localData.firstName" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter first name" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Last Name <span class="text-red-500">*</span>
                </label>
                <input v-model="localData.lastName" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter last name" required>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organization/Company</label>
                <input v-model="localData.organization" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter company name">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input v-model="localData.title" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter Title">
            </div>
        </div>

        <!-- Contact Information -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input v-model="localData.email" type="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter email address">
        </div>

        <!-- Phone Numbers -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Work Phone</label>
                <input v-model="localData.phoneWork" type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter phone work number">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Phone</label>
                <input v-model="localData.phoneMobile" type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter phone number">
            </div>

            <!-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Home Phone</label>
                <input v-model="localData.phoneHome" type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter home phone number">
            </div> -->

            <!-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fax</label>
                <input v-model="localData.fax" type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter fax number">
            </div> -->
        </div>

        <!-- Address -->
        <!-- <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">Address</label>

            <input v-model="localData.addressStreet" type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter street address">

            <div class="grid grid-cols-2 gap-4">
                <input v-model="localData.addressCity" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter city">
                <input v-model="localData.addressState" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter state/province">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input v-model="localData.addressZip" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter ZIP/postal code">
                <input v-model="localData.addressCountry" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter country">
            </div>
        </div> -->

        <!-- Additional Information -->
        <!-- <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
            <input v-model="localData.website" type="url"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter website URL">
        </div> -->

        <!-- <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
            <input v-model="localData.birthday" type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div> -->

        <!-- <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea v-model="localData.note" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Additional notes about the contact"></textarea>
        </div> -->

        <!-- Social Profiles -->
        <!-- <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700">Social Profiles</label>
                <button @click="addSocialProfile" type="button" class="text-sm text-blue-600 hover:text-blue-800">
                    + Add Profile
                </button>
            </div>

            <div v-for="(profile, index) in localData.socialProfiles" :key="index" class="flex gap-2 mb-2">
                <select v-model="profile.type"
                    class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="linkedin">LinkedIn</option>
                    <option value="twitter">Twitter</option>
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                    <option value="youtube">YouTube</option>
                </select>
                <input v-model="profile.url" type="url"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Profile URL">
                <button @click="removeSocialProfile(index)" type="button"
                    class="px-3 py-2 text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div> -->

        <p class="text-xs text-gray-500">
            <span class="text-red-500">*</span> Required fields for vCard format
        </p>
    </div>
</template>