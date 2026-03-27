<!-- Resources/js/Components/MyAAIB/Jobs/ViewApplicationDetails.vue -->
<script setup>
import { ref, watch, computed } from 'vue';
import { Modal, Tag, Divider, Button, message, Space } from 'ant-design-vue';
import {
    FilePdfOutlined,
    DownloadOutlined,
    UserOutlined,
    MailOutlined,
    PhoneOutlined,
    EnvironmentOutlined,
    BankOutlined,
    IdcardOutlined,
    DeleteOutlined,
    EyeOutlined,
    CloseOutlined,
    MergeCellsOutlined
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import axios from 'axios';

const props = defineProps({
    open: Boolean,
    application: Object,
});

const emit = defineEmits(['update:open', 'deleted']);

const localOpen = ref(props.open);
const downloading = ref(false);
const merging = ref(false);
const deleting = ref(false);

watch(() => props.open, val => localOpen.value = val);
watch(localOpen, val => emit('update:open', val));

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return dayjs(date).format('MMMM D, YYYY HH:mm');
};

// Get file URL
const getFileUrl = (filePath) => {
    if (!filePath) return null;
    if (filePath.startsWith('http')) return filePath;
    let cleanPath = filePath.replace(/^storage\//, '');
    cleanPath = cleanPath.replace(/^\/+/, '');
    return `/storage/${cleanPath}`;
};

// Download file
const downloadFile = async (filePath, fileName) => {
    if (!filePath) {
        message.warning('File not available');
        return;
    }

    downloading.value = true;
    try {
        const url = getFileUrl(filePath);
        const link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        message.success('Download started');
    } catch (error) {
        console.error('Download failed:', error);
        message.error('Failed to download file');
    } finally {
        downloading.value = false;
    }
};

// Handle delete
const handleDelete = async () => {
    if (!props.application?.id) {
        message.error('Invalid application');
        return;
    }

    deleting.value = true;
    try {
        await axios.delete(`/job-applications/${props.application.id}`);
        message.success('Application deleted successfully');
        emit('deleted', props.application.id);
        localOpen.value = false;
    } catch (error) {
        console.error('Delete failed:', error);
        message.error(error.response?.data?.message || 'Failed to delete application');
    } finally {
        deleting.value = false;
    }
};

// Generate cover page HTML for PDF
const generateCoverPageHtml = () => {
    const app = safeApplication.value;
    const date = new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Application Cover Page</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 40px;
                    background: white;
                }
                .cover-page {
                    max-width: 800px;
                    margin: 0 auto;
                    border: 2px solid #14b8a6;
                    padding: 40px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #14b8a6;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .header h1 {
                    color: #0f766e;
                    font-size: 28px;
                    margin: 0 0 10px 0;
                }
                .header p {
                    color: #6b7280;
                    margin: 0;
                }
                .section {
                    margin-bottom: 25px;
                }
                .section-title {
                    font-size: 18px;
                    font-weight: bold;
                    color: #14b8a6;
                    border-left: 4px solid #14b8a6;
                    padding-left: 12px;
                    margin-bottom: 15px;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: 1fr 2fr;
                    gap: 12px;
                    margin-bottom: 10px;
                }
                .info-label {
                    font-weight: 600;
                    color: #374151;
                }
                .info-value {
                    color: #1f2937;
                }
                .documents-list {
                    margin-top: 10px;
                }
                .doc-item {
                    padding: 8px 0;
                    border-bottom: 1px solid #e5e7eb;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .doc-icon {
                    font-size: 20px;
                }
                .doc-name {
                    color: #374151;
                }
                .footer {
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 1px solid #e5e7eb;
                    text-align: center;
                    color: #9ca3af;
                    font-size: 12px;
                }
                .badge {
                    display: inline-block;
                    background: #d1fae5;
                    color: #065f46;
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>
            <div class="cover-page">
                <div class="header">
                    <h1>Job Application Package</h1>
                    <p>Generated on ${date}</p>
                    <div style="margin-top: 10px;">
                        <span class="badge">Application #${app.id || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Applicant Information</div>
                    <div class="info-grid">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">${app.name || 'Not provided'}</div>
                        
                        <div class="info-label">Email Address:</div>
                        <div class="info-value">${app.email || 'Not provided'}</div>
                        
                        <div class="info-label">Phone Number:</div>
                        <div class="info-value">${app.phone || 'Not provided'}</div>
                        
                        <div class="info-label">Address:</div>
                        <div class="info-value">${app.address || 'Not provided'}</div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Professional Information</div>
                    <div class="info-grid">
                        <div class="info-label">Current Position:</div>
                        <div class="info-value">${app.current_position || 'Not provided'}</div>
                        
                        <div class="info-label">Current Employer:</div>
                        <div class="info-value">${app.current_employer || 'Not provided'}</div>
                    </div>
                </div>
                
                ${app.job ? `
                <div class="section">
                    <div class="section-title">Applied Position</div>
                    <div class="info-grid">
                        <div class="info-label">Job Title:</div>
                        <div class="info-value">${app.job.title || 'N/A'}</div>
                        
                        <div class="info-label">Job Type:</div>
                        <div class="info-value">${app.job.type || 'N/A'}</div>
                        
                        <div class="info-label">Location:</div>
                        <div class="info-value">${app.job.location || 'N/A'}</div>
                    </div>
                </div>
                ` : ''}
                
                <div class="section">
                    <div class="section-title">Submitted Documents</div>
                    <div class="documents-list">
                        ${filteredDocuments.value.map(doc => `
                            <div class="doc-item">
                                <span class="doc-icon">${doc.icon}</span>
                                <span class="doc-name">${doc.label}</span>
                                <span style="margin-left: auto; font-size: 12px; color: #6b7280;">${getFileName(doc.file)}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="footer">
                    <p>This document contains the complete application package submitted by the applicant.</p>
                    <p>Total Documents: ${filteredDocuments.value.length}</p>
                </div>
            </div>
        </body>
        </html>
    `;
};

// Merge all PDFs into one document
const mergeAllPDFs = async () => {
    if (!filteredDocuments.value.length) {
        message.warning('No documents available to merge');
        return;
    }

    merging.value = true;
    
    try {
        // Dynamically import pdf-lib
        const { PDFDocument, StandardFonts, rgb } = await import('pdf-lib');
        
        // Create a new PDF document for the merged result
        const mergedPdf = await PDFDocument.create();
        
        // Generate cover page as PDF
        const coverPageHtml = generateCoverPageHtml();
        
        // Convert HTML to PDF using html2pdf.js or similar
        // For this example, we'll create a text-based cover page using pdf-lib
        const coverPage = await PDFDocument.create();
        const page = coverPage.addPage([600, 800]);
        const font = await coverPage.embedFont(StandardFonts.Helvetica);
        const boldFont = await coverPage.embedFont(StandardFonts.HelveticaBold);
        
        // Add cover page content
        let y = 750;
        const lineHeight = 25;
        
        // Title
        page.drawText('JOB APPLICATION PACKAGE', {
            x: 50,
            y: y,
            size: 24,
            font: boldFont,
            color: rgb(0.2, 0.5, 0.4)
        });
        y -= lineHeight * 2;
        
        // Date
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        page.drawText(`Generated: ${currentDate}`, {
            x: 50,
            y: y,
            size: 12,
            font: font,
            color: rgb(0.4, 0.4, 0.4)
        });
        y -= lineHeight * 2;
        
        // Application ID
        page.drawText(`Application #: ${safeApplication.value.id || 'N/A'}`, {
            x: 50,
            y: y,
            size: 12,
            font: boldFont,
            color: rgb(0, 0, 0)
        });
        y -= lineHeight;
        
        page.drawText(`Applicant: ${safeApplication.value.name || 'N/A'}`, {
            x: 50,
            y: y,
            size: 12,
            font: boldFont,
            color: rgb(0, 0, 0)
        });
        y -= lineHeight * 2;
        
        // Section: Personal Information
        page.drawText('PERSONAL INFORMATION', {
            x: 50,
            y: y,
            size: 14,
            font: boldFont,
            color: rgb(0.2, 0.5, 0.4)
        });
        y -= lineHeight;
        
        const fields = [
            { label: 'Full Name:', value: safeApplication.value.name || 'Not provided' },
            { label: 'Email:', value: safeApplication.value.email || 'Not provided' },
            { label: 'Phone:', value: safeApplication.value.phone || 'Not provided' },
            { label: 'Address:', value: safeApplication.value.address || 'Not provided' },
            { label: 'Current Position:', value: safeApplication.value.current_position || 'Not provided' },
            { label: 'Current Employer:', value: safeApplication.value.current_employer || 'Not provided' }
        ];
        
        fields.forEach(field => {
            page.drawText(`${field.label}`, {
                x: 50,
                y: y,
                size: 10,
                font: boldFont,
                color: rgb(0.3, 0.3, 0.3)
            });
            page.drawText(`${field.value}`, {
                x: 150,
                y: y,
                size: 10,
                font: font,
                color: rgb(0, 0, 0)
            });
            y -= lineHeight;
        });
        
        y -= lineHeight;
        
        // Section: Documents
        page.drawText('SUBMITTED DOCUMENTS', {
            x: 50,
            y: y,
            size: 14,
            font: boldFont,
            color: rgb(0.2, 0.5, 0.4)
        });
        y -= lineHeight;
        
        filteredDocuments.value.forEach((doc, index) => {
            page.drawText(`${index + 1}. ${doc.label}`, {
                x: 50,
                y: y,
                size: 10,
                font: font,
                color: rgb(0, 0, 0)
            });
            y -= lineHeight;
        });
        
        // Copy cover page to merged PDF
        const [copiedCoverPage] = await mergedPdf.copyPages(coverPage, coverPage.getPageIndices());
        mergedPdf.addPage(copiedCoverPage);
        
        // Fetch and add each document PDF
        for (const doc of filteredDocuments.value) {
            if (doc.file) {
                try {
                    const pdfUrl = getFileUrl(doc.file);
                    const pdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
                    const pdfDoc = await PDFDocument.load(pdfBytes);
                    const pages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                    pages.forEach(page => mergedPdf.addPage(page));
                } catch (error) {
                    console.error(`Failed to load PDF for ${doc.label}:`, error);
                    message.warning(`Could not load ${doc.label}, skipping...`);
                }
            }
        }
        
        // Save the merged PDF
        const mergedPdfBytes = await mergedPdf.save();
        
        // Create download link
        const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `${safeApplication.value.name || 'applicant'}_${safeApplication.value.id || 'application'}_complete_package.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        message.success('All documents merged successfully!');
    } catch (error) {
        console.error('Error merging PDFs:', error);
        message.error('Failed to merge documents. Please try again.');
    } finally {
        merging.value = false;
    }
};

// Safe application object
const safeApplication = computed(() => {
    return props.application || {};
});

// Document sections with safe file checking
const documents = computed(() => {
    const app = safeApplication.value;
    return [
        {
            key: 'cv',
            label: 'Curriculum Vitae (CV)',
            file: app.cv && app.cv !== null ? app.cv : null,
            required: true,
            icon: '📄'
        },
        {
            key: 'nrc',
            label: 'National Registration Card',
            file: app.nrc && app.nrc !== null ? app.nrc : null,
            required: true,
            icon: '🆔'
        },
        {
            key: 'grade12',
            label: 'Grade 12 Certificate',
            file: app.grade12 && app.grade12 !== null ? app.grade12 : null,
            required: true,
            icon: '🎓'
        },
        {
            key: 'degree',
            label: 'Degree Certificate',
            file: app.degree && app.degree !== null ? app.degree : null,
            required: false,
            icon: '📜'
        },
        {
            key: 'masters',
            label: 'Masters Certificate',
            file: app.masters && app.masters !== null ? app.masters : null,
            required: false,
            icon: '🏆'
        },
        {
            key: 'other_documents',
            label: 'Other Documents',
            file: app.other_documents && app.other_documents !== null ? app.other_documents : null,
            required: false,
            icon: '📁'
        },
    ];
});

// Check if there are any documents
const hasDocuments = computed(() => {
    return documents.value.some(doc => doc.file && doc.file !== null);
});

const filteredDocuments = computed(() => {
    return documents.value.filter(doc => doc && doc.file);
});

// Get file name from path
const getFileName = (filePath) => {
    if (!filePath) return '';
    return filePath.split('/').pop();
};
</script>

<template>
    <Modal v-model:open="localOpen" title="Application Details" :footer="null" width="1000px"
        :bodyStyle="{ padding: '24px' }" class="application-details-modal" :maskClosable="false">
        <div v-if="safeApplication && safeApplication.id" class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-start border-b border-gray-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ safeApplication.name || 'N/A' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Applied on {{ formatDate(safeApplication.created_at) }}
                    </p>
                </div>
                <Tag color="blue" class="status-tag">
                    <EyeOutlined class="mr-1" />
                    Application #{{ safeApplication.id }}
                </Tag>
            </div>

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <UserOutlined class="text-teal-600" />
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <UserOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Full Name</span>
                        </div>
                        <div class="text-gray-900 font-medium">{{ safeApplication.name || 'Not provided' }}</div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <MailOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Email Address</span>
                        </div>
                        <div class="text-gray-900">
                            <a :href="`mailto:${safeApplication.email}`" class="text-teal-600 hover:text-teal-700">
                                {{ safeApplication.email || 'Not provided' }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <PhoneOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Phone Number</span>
                        </div>
                        <div class="text-gray-900">{{ safeApplication.phone || 'Not provided' }}</div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <EnvironmentOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Address</span>
                        </div>
                        <div class="text-gray-900">{{ safeApplication.address || 'Not provided' }}</div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <BankOutlined class="text-teal-600" />
                    Professional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <IdcardOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Current Position</span>
                        </div>
                        <div class="text-gray-900">{{ safeApplication.current_position || 'Not provided' }}</div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex items-center gap-2 text-gray-600 mb-1">
                            <BankOutlined />
                            <span class="text-xs font-medium uppercase tracking-wide">Current Employer</span>
                        </div>
                        <div class="text-gray-900">{{ safeApplication.current_employer || 'Not provided' }}</div>
                    </div>
                </div>
            </div>

            <!-- Job Information -->
            <div v-if="safeApplication.job">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <FilePdfOutlined class="text-teal-600" />
                    Applied Position
                </h3>
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-lg p-4 border border-teal-200">
                    <p class="font-semibold text-gray-900 text-lg">{{ safeApplication.job.title || 'N/A' }}</p>
                    <div class="flex gap-3 mt-2 text-sm text-gray-600">
                        <Tag color="blue">{{ safeApplication.job.type || 'N/A' }}</Tag>
                        <Tag>{{ safeApplication.job.location || 'N/A' }}</Tag>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <FilePdfOutlined class="text-red-500" />
                        Supporting Documents
                    </h3>
                    <Button 
                        v-if="hasDocuments" 
                        type="primary"
                        @click="mergeAllPDFs" 
                        :loading="merging"
                        class="bg-teal-600 hover:bg-teal-700 border-teal-600"
                    >
                        <MergeCellsOutlined class="mr-1" />
                        Download All as One PDF
                    </Button>
                </div>

                <div v-if="hasDocuments" class="space-y-3">
                    <div v-for="doc in filteredDocuments" :key="doc.key"
                        class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ doc.icon }}</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ doc.label }}</p>
                                <p class="text-xs text-gray-500 font-mono">
                                    {{ getFileName(doc.file) }}
                                </p>
                            </div>
                            <Tag v-if="doc.required" color="red" size="small" class="ml-2">Required</Tag>
                            <Tag v-else color="default" size="small" class="ml-2">Optional</Tag>
                        </div>
                        <Button size="small"
                            @click="downloadFile(doc.file, `${safeApplication.name || 'applicant'}_${doc.key}.pdf`)"
                            :loading="downloading" class="border-teal-600 text-teal-600 hover:bg-teal-50">
                            <DownloadOutlined /> Download
                        </Button>
                    </div>
                </div>

                <!-- No Documents Message -->
                <div v-else class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                    <FilePdfOutlined class="text-4xl text-gray-400 mb-2" />
                    <p class="text-gray-500">No documents uploaded with this application</p>
                </div>
            </div>

            <!-- Footer Actions -->
            <Divider />
            <div class="flex justify-end gap-3">
                <Button @click="localOpen = false" class="border-gray-300 hover:border-teal-600">
                    <CloseOutlined /> Close
                </Button>
                <Button danger @click="handleDelete" :loading="deleting"
                    class="border-red-600 text-red-600 hover:bg-red-50">
                    <DeleteOutlined /> Delete Application
                </Button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <div class="text-6xl mb-4">📄</div>
            <p class="text-gray-500 text-lg">No application details available</p>
            <Button @click="localOpen = false" class="mt-4">Close</Button>
        </div>
    </Modal>
</template>

<style scoped>
.application-details-modal :deep(.ant-modal-header) {
    border-bottom: 2px solid #f0f0f0;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
}

.application-details-modal :deep(.ant-modal-title) {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.application-details-modal :deep(.ant-modal-body) {
    padding: 0;
    max-height: 80vh;
    overflow-y: auto;
}

.status-tag {
    padding: 4px 12px;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Custom scrollbar */
.application-details-modal :deep(.ant-modal-body)::-webkit-scrollbar {
    width: 8px;
}

.application-details-modal :deep(.ant-modal-body)::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.application-details-modal :deep(.ant-modal-body)::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.application-details-modal :deep(.ant-modal-body)::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Animation */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.application-details-modal :deep(.ant-modal) {
    animation: slideUp 0.3s ease-out;
}
</style>