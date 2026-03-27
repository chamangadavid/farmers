<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, reactive } from 'vue';
import axios from 'axios';
import { message, Input, Upload, Button } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';

const props = defineProps({
    job: Object
});

// Form fields
const form = ref({
    name: '',
    email: '',
    phone: '',
    address: '',
    current_position: '',
    current_employer: '',
});

// Track touched fields for validation
const touched = ref({
    name: false,
    email: false,
    phone: false
});

// File storage - store the actual File objects
const files = ref({
    cv: null,
    nrc: null,
    grade12: null,
    degree: null,
    masters: null,
    other: null,
});

// File lists for UI display
const fileLists = reactive({
    cv: [],
    nrc: [],
    grade12: [],
    degree: [],
    masters: [],
    other: [],
});

const submitting = ref(false);

// Handle file selection
const handleFile = (field, file) => {
    // Validate file size (5MB limit)
    if (file.size > 5 * 1024 * 1024) {
        message.error('File size must be less than 5MB');
        return false;
    }

    // Store the actual file
    files.value[field] = file;

    // Update UI file list
    fileLists[field] = [{
        uid: file.uid || Date.now(),
        name: file.name,
        status: 'done',
        url: URL.createObjectURL(file)
    }];

    message.success(`${file.name} uploaded successfully`);
    return false; // Prevent auto upload
};

// Handle file removal
const handleRemove = (field) => {
    files.value[field] = null;

    // Revoke object URL to avoid memory leaks
    if (fileLists[field][0]?.url) {
        URL.revokeObjectURL(fileLists[field][0].url);
    }

    fileLists[field] = [];
    message.info('File removed');
};

// Submit application
const submitApplication = async () => {
    // Mark all fields as touched for validation
    touched.value.name = true;
    touched.value.email = true;
    touched.value.phone = true;

    // Validate required fields
    if (!form.value.name || !form.value.name.trim()) {
        message.error('Please enter your full name');
        return;
    }

    if (!form.value.email || !form.value.email.trim()) {
        message.error('Please enter your email address');
        return;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
    if (!emailRegex.test(form.value.email)) {
        message.error('Please enter a valid email address');
        return;
    }

    if (!form.value.phone || !form.value.phone.trim()) {
        message.error('Please enter your phone number');
        return;
    }

    // Validate required files
    const requiredFiles = ['cv', 'nrc', 'grade12'];
    const missingFiles = requiredFiles.filter(field => !files.value[field]);

    if (missingFiles.length > 0) {
        const fileNames = missingFiles.map(f => {
            if (f === 'cv') return 'CV';
            if (f === 'nrc') return 'NRC';
            if (f === 'grade12') return 'Grade 12 Certificate';
            return f;
        }).join(', ');
        message.error(`Please upload: ${fileNames}`);
        return;
    }

    submitting.value = true;

    const formData = new FormData();

    // Append text fields
    formData.append('job_id', props.job.id);
    formData.append('name', form.value.name.trim());
    formData.append('email', form.value.email.trim());
    formData.append('phone', form.value.phone.trim());
    formData.append('address', form.value.address?.trim() || '');
    formData.append('current_position', form.value.current_position?.trim() || '');
    formData.append('current_employer', form.value.current_employer?.trim() || '');

    // Append files
    Object.keys(files.value).forEach(key => {
        if (files.value[key]) {
            const fieldName = key === 'other' ? 'other_documents' : key;
            formData.append(fieldName, files.value[key]);
        }
    });

    try {
        const response = await axios.post('/job-apply', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            timeout: 60000 // 60 seconds timeout for file upload
        });

        message.success({
            content: 'Application submitted successfully! We will contact you soon.',
            duration: 5
        });

        // Reset form after successful submission
        form.value = {
            name: '',
            email: '',
            phone: '',
            address: '',
            current_position: '',
            current_employer: '',
        };

        // Reset touched state
        touched.value = {
            name: false,
            email: false,
            phone: false
        };

        // Reset files
        Object.keys(files.value).forEach(key => {
            if (fileLists[key][0]?.url) {
                URL.revokeObjectURL(fileLists[key][0].url);
            }
            files.value[key] = null;
            fileLists[key] = [];
        });

    } catch (error) {
        console.error('Submission error:', error);

        if (error.code === 'ECONNABORTED') {
            message.error('Upload timeout. Please check your connection and try again.');
        } else if (error.response?.data?.errors) {
            // Display validation errors
            const errors = error.response.data.errors;
            Object.values(errors).forEach(errorMsg => {
                message.error(errorMsg[0]);
            });
        } else if (error.response?.data?.message) {
            message.error(error.response.data.message);
        } else {
            message.error('Failed to submit application. Please try again.');
        }
    } finally {
        submitting.value = false;
    }
};

</script>


<template>
    <PageLayout title="Job Details">

        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 space-y-8">

            <!-- JOB DETAILS CARD -->
            <div
                class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">{{ job.title }}</h1>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="px-3 py-1.5 bg-teal-100 text-teal-700 rounded-full text-sm font-semibold">
                            {{ job.type }}
                        </span>
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            📍 {{ job.location }}
                        </span>
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            ⏰ Deadline: {{ job.deadline?.split('T')[0] }}
                        </span>
                    </div>

                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ job.description }}</p>
                    </div>

                    <!-- Additional Job Info if available -->
                    <div v-if="job.requirements?.length" class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Key Requirements:</h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="req in job.requirements.slice(0, 5)" :key="req"
                                class="px-2 py-1 bg-gray-50 text-gray-600 rounded text-xs border border-gray-200">
                                {{ req }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APPLICATION FORM CARD -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Apply for this Position
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Please fill out all required fields marked with *</p>
                </div>

                <div class="p-6">
                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h3
                            class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span class="w-1 h-4 bg-teal-600 rounded-full"></span>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <Input v-model:value="form.name" placeholder="Enter your full name" class="rounded-lg"
                                    :class="{ 'border-red-300': form.name === '' && touched.name }" />
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <Input v-model:value="form.email" placeholder="your.email@example.com" type="email"
                                    class="rounded-lg" />
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <Input v-model:value="form.phone" placeholder="+260 XXX XXX XXX" class="rounded-lg" />
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Address
                                </label>
                                <Input v-model:value="form.address" placeholder="Your residential address"
                                    class="rounded-lg" />
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="mb-8">
                        <h3
                            class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span class="w-1 h-4 bg-teal-600 rounded-full"></span>
                            Professional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Current Position
                                </label>
                                <Input v-model:value="form.current_position" placeholder="e.g., Senior Investigator"
                                    class="rounded-lg" />
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-700">
                                    Current Employer
                                </label>
                                <Input v-model:value="form.current_employer" placeholder="Company/Organization name"
                                    class="rounded-lg" />
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="mb-6">
                        <h3
                            class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <span class="w-1 h-4 bg-teal-600 rounded-full"></span>
                            Required Documents
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Please upload PDF documents only (Max size: 5MB each)</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- CV Upload -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Curriculum Vitae (CV) <span class="text-red-500">*</span>
                                </label>
                                <Upload :before-upload="(file) => handleFile('cv', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.cv"
                                    @remove="() => handleRemove('cv')" class="upload-component">
                                    <Button class="border-teal-600 text-teal-600 hover:bg-teal-50 rounded-lg">
                                        <UploadOutlined /> Upload CV
                                    </Button>
                                </Upload>
                                <p class="text-xs text-gray-400">PDF format required</p>
                            </div>

                            <!-- NRC Upload -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    National Registration Card (NRC) <span class="text-red-500">*</span>
                                </label>
                                <Upload :before-upload="(file) => handleFile('nrc', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.nrc"
                                    @remove="() => handleRemove('nrc')" class="upload-component">
                                    <Button class="border-teal-600 text-teal-600 hover:bg-teal-50 rounded-lg">
                                        <UploadOutlined /> Upload NRC
                                    </Button>
                                </Upload>
                                <p class="text-xs text-gray-400">PDF format required</p>
                            </div>

                            <!-- Grade 12 Certificate -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Grade 12 Certificate <span class="text-red-500">*</span>
                                </label>
                                <Upload :before-upload="(file) => handleFile('grade12', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.grade12"
                                    @remove="() => handleRemove('grade12')" class="upload-component">
                                    <Button class="border-teal-600 text-teal-600 hover:bg-teal-50 rounded-lg">
                                        <UploadOutlined /> Upload Certificate
                                    </Button>
                                </Upload>
                                <p class="text-xs text-gray-400">PDF format required</p>
                            </div>

                            <!-- Degree Upload (Optional) -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Degree Certificate <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <Upload :before-upload="(file) => handleFile('degree', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.degree"
                                    @remove="() => handleRemove('degree')" class="upload-component">
                                    <Button
                                        class="border-gray-300 text-gray-700 hover:border-teal-600 hover:text-teal-600 rounded-lg">
                                        <UploadOutlined /> Upload Degree
                                    </Button>
                                </Upload>
                            </div>

                            <!-- Masters Upload (Optional) -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Masters Certificate <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <Upload :before-upload="(file) => handleFile('masters', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.masters"
                                    @remove="() => handleRemove('masters')" class="upload-component">
                                    <Button
                                        class="border-gray-300 text-gray-700 hover:border-teal-600 hover:text-teal-600 rounded-lg">
                                        <UploadOutlined /> Upload Masters
                                    </Button>
                                </Upload>
                            </div>

                            <!-- Other Documents (Optional) -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Other Supporting Documents
                                </label>
                                <Upload :before-upload="(file) => handleFile('other', file)" :show-upload-list="true"
                                    accept=".pdf" :max-count="1" :file-list="fileLists.other"
                                    @remove="() => handleRemove('other')" class="upload-component">
                                    <Button
                                        class="border-gray-300 text-gray-700 hover:border-teal-600 hover:text-teal-600 rounded-lg">
                                        <UploadOutlined /> Upload Documents
                                    </Button>
                                </Upload>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <Button type="primary" @click="submitApplication" :loading="submitting" block
                            class="submit-button h-12 text-base font-semibold rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 shadow-md hover:shadow-lg transition-all duration-300">
                            <svg v-if="!submitting" class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Application
                        </Button>
                        <p class="text-center text-xs text-gray-400 mt-3">
                            By submitting this application, you confirm that all information provided is accurate and
                            complete.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </PageLayout>
</template>


<style scoped>
/* Custom Input Styling */
:deep(.ant-input) {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    padding: 8px 12px;
}

:deep(.ant-input:hover) {
    border-color: #14b8a6;
}

:deep(.ant-input:focus) {
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Upload Component Styling */
:deep(.ant-upload) {
    width: 100%;
}

:deep(.ant-btn) {
    border-radius: 8px;
    transition: all 0.3s ease;
}

:deep(.ant-btn:hover) {
    transform: translateY(-1px);
}

/* File list styling */
:deep(.ant-upload-list) {
    margin-top: 8px;
}

:deep(.ant-upload-list-item) {
    border-radius: 6px;
    font-size: 12px;
    padding: 4px 8px;
}

/* Submit button */
.submit-button {
    border: none;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.submit-button:active {
    transform: translateY(0);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .max-w-5xl {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    :deep(.ant-input) {
        font-size: 14px;
    }
}

/* Animation for form sections */
.bg-white {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Prose styling for job description */
.prose {
    line-height: 1.6;
}

/* Required field indicator */
label .text-red-500 {
    font-weight: bold;
}

/* Card hover effects */
.rounded-2xl {
    transition: all 0.3s ease;
}

/* Upload component container */
.upload-component {
    display: block;
}
</style>