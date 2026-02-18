// resources/js/Components/forms/SocialForm.vue
<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:data']);

const socialPlatforms = {
    facebook: {
        name: 'Facebook',
        icon: 'fab fa-facebook',
        placeholder: 'facebook.com/username',
        urlPattern: 'https://facebook.com/',
        color: 'text-blue-600'
    },
    twitter: {
        name: 'Twitter',
        icon: 'fab fa-twitter',
        placeholder: 'twitter.com/username',
        urlPattern: 'https://twitter.com/',
        color: 'text-sky-500'
    },
    linkedin: {
        name: 'LinkedIn',
        icon: 'fab fa-linkedin',
        placeholder: 'linkedin.com/in/username',
        urlPattern: 'https://linkedin.com/in/',
        color: 'text-blue-700'
    },
    instagram: {
        name: 'Instagram',
        icon: 'fab fa-instagram',
        placeholder: 'instagram.com/username',
        urlPattern: 'https://instagram.com/',
        color: 'text-pink-600'
    },
    youtube: {
        name: 'YouTube',
        icon: 'fab fa-youtube',
        placeholder: 'youtube.com/@channel',
        urlPattern: 'https://youtube.com/',
        color: 'text-red-600'
    },
    tiktok: {
        name: 'TikTok',
        icon: 'fab fa-tiktok',
        placeholder: 'tiktok.com/@username',
        urlPattern: 'https://tiktok.com/@',
        color: 'text-black'
    },
    pinterest: {
        name: 'Pinterest',
        icon: 'fab fa-pinterest',
        placeholder: 'pinterest.com/username',
        urlPattern: 'https://pinterest.com/',
        color: 'text-red-700'
    },
    snapchat: {
        name: 'Snapchat',
        icon: 'fab fa-snapchat',
        placeholder: 'snapchat.com/add/username',
        urlPattern: 'https://snapchat.com/add/',
        color: 'text-yellow-600'
    }
};

// Get the current platform from the component's usage or data
const platform = computed(() => {
    // This will be set by the parent component based on the selected type
    return props.data?.platform || 'facebook';
});

const localData = ref({
    platform: platform.value,
    username: props.data?.username || '',
    url: props.data?.url || '',
    profileType: props.data?.profileType || 'profile', // profile, page, group, video
    contentType: props.data?.contentType || '', // for specific content
    contentId: props.data?.contentId || '',
    showFollowers: props.data?.showFollowers || false,
    showPosts: props.data?.showPosts || false
});

// Update URL when username changes
const updateUrl = () => {
    const platformInfo = socialPlatforms[localData.value.platform];
    if (platformInfo && localData.value.username) {
        localData.value.url = platformInfo.urlPattern + localData.value.username;
    }
};

// Watch for platform changes
watch(() => localData.value.platform, () => {
    localData.value.username = '';
    localData.value.url = '';
});

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });

const currentPlatform = computed(() => {
    return socialPlatforms[localData.value.platform] || socialPlatforms.facebook;
});
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center gap-2 mb-4">
            <i :class="[currentPlatform.icon, currentPlatform.color, 'text-2xl']"></i>
            <h3 class="font-semibold text-lg">{{ currentPlatform.name }} Profile</h3>
        </div>

        <!-- Platform Selection (hidden if already selected) -->
        <div v-if="!props.data?.platform" class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Platform</label>
            <div class="grid grid-cols-4 gap-2">
                <button v-for="(plat, key) in socialPlatforms" :key="key" @click="localData.platform = key"
                    type="button" class="p-2 border rounded-lg text-center hover:bg-gray-50"
                    :class="{ 'border-blue-500 bg-blue-50': localData.platform === key }">
                    <i :class="[plat.icon, plat.color, 'text-xl']"></i>
                    <span class="text-xs block mt-1">{{ plat.name }}</span>
                </button>
            </div>
        </div>

        <!-- Profile Information -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ currentPlatform.name }} Username/Handle <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <span
                    class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-l-md text-gray-600">
                    {{ currentPlatform.urlPattern.replace('https://', '') }}
                </span>
                <input v-model="localData.username" @input="updateUrl" type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:ring-blue-500 focus:border-blue-500"
                    :placeholder="currentPlatform.placeholder" required>
            </div>
        </div>

        <!-- Profile Type Selection (for platforms with multiple types) -->
        <div v-if="['facebook', 'youtube'].includes(localData.platform)">
            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Type</label>
            <div class="flex gap-4">
                <label class="flex items-center">
                    <input type="radio" v-model="localData.profileType" value="profile"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Profile</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" v-model="localData.profileType" value="page"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Page</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" v-model="localData.profileType" value="group"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="ml-2 text-sm text-gray-700">Group</span>
                </label>
            </div>
        </div>

        <!-- Specific Content (optional) -->
        <div v-if="localData.profileType !== 'profile'" class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content Type</label>
                <select v-model="localData.contentType"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Link to profile/page</option>
                    <option value="post">Specific Post</option>
                    <option value="video">Specific Video</option>
                    <option value="playlist">Playlist</option>
                </select>
            </div>

            <div v-if="localData.contentType">
                <label class="block text-sm font-medium text-gray-700 mb-1">Content ID/URL</label>
                <input v-model="localData.contentId" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter post/video ID or full URL">
            </div>
        </div>

        <!-- Display Options -->
        <div class="space-y-2 pt-2">
            <label class="flex items-center">
                <input type="checkbox" v-model="localData.showFollowers"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-700">Show follower count when scanned</span>
            </label>

            <label class="flex items-center">
                <input type="checkbox" v-model="localData.showPosts"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-700">Show recent posts preview</span>
            </label>
        </div>

        <!-- Generated URL Preview -->
        <div v-if="localData.url" class="bg-gray-50 p-3 rounded-md mt-4">
            <p class="text-sm text-gray-600">
                <span class="font-medium">Profile URL:</span><br>
                <a :href="localData.url" target="_blank" class="text-blue-600 break-all hover:underline">
                    {{ localData.url }}
                </a>
            </p>
        </div>

        <div class="bg-blue-50 p-3 rounded-md">
            <p class="text-xs text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                When scanned, this QR code will open your {{ currentPlatform.name }} profile or content.
            </p>
        </div>
    </div>
</template>