<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateDocument from './CreateDocument.vue';
import { Tabs, Button, Input, message, Popconfirm, Upload, Tag, Empty, Spin, Tooltip, } from 'ant-design-vue';
import { 
  FolderOutlined, 
  FileOutlined, 
  UploadOutlined, 
  DeleteOutlined, 
  DownloadOutlined,
  EditOutlined,
  PlusOutlined,
  ReloadOutlined,
  FilePdfOutlined,
  FileImageOutlined,
  FileTextOutlined,
  FileExcelOutlined,
  FileWordOutlined,
  EyeOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';

const folders = ref([]);
const selectedFolder = ref(null);
const showCreateFolder = ref(false);
const showUpload = ref(false);
const uploading = ref(false);
const loading = ref(false);

const fetchFolders = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/documents/folders');
        folders.value = res.data;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch folders');
    } finally {
        loading.value = false;
    }
};

// const openFolder = (folder) => {
//     selectedFolder.value = folder;
// };

const openFolder = (folder) => {
    console.log('SELECTED FOLDER:', folder);
    console.log('DOCUMENTS:', folder.documents);

    if (folder.documents?.length) {
        console.log('FIRST DOCUMENT:', folder.documents[0]);
    }

    selectedFolder.value = folder;
};

const refreshFolder = async () => {
    const res = await axios.get('/documents/folders');
    folders.value = res.data;
    if (selectedFolder.value) {
        selectedFolder.value = folders.value.find(
            f => f.id === selectedFolder.value.id
        );
    }
};

const deleteFile = async (id) => {
    try {
        await axios.delete(`/documents/${id}`);
        message.success('File deleted successfully');
        refreshFolder();
    } catch (error) {
        message.error('Failed to delete file');
    }
};

const customRequest = async ({ file, onSuccess, onError }) => {
    try {
        const formData = new FormData();
        formData.append('file', file);

        if (!selectedFolder.value?.id) {
            message.error('Please select a folder first');
            return onError(new Error('No folder selected'));
        }

        formData.append('folder_id', selectedFolder.value.id);

        const res = await axios.post('/documents/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        message.success('File uploaded successfully');
        await refreshFolder();
        onSuccess(res.data);
    } catch (e) {
        console.log(e.response?.data || e);
        message.error(e.response?.data?.message || 'Upload failed');
        onError(e);
    }
};

// Get file icon based on extension
const getFileIcon = (fileName) => {
    const ext = fileName?.split('.').pop()?.toLowerCase();
    if (ext === 'pdf') return FilePdfOutlined;
    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) return FileImageOutlined;
    if (['xls', 'xlsx', 'csv'].includes(ext)) return FileExcelOutlined;
    if (['doc', 'docx'].includes(ext)) return FileWordOutlined;
    if (['txt', 'md'].includes(ext)) return FileTextOutlined;
    return FileOutlined;
};

// Format file size
const formatFileSize = (bytes) => {
    if (!bytes) return 'N/A';
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return `${(bytes / Math.pow(1024, i)).toFixed(2)} ${sizes[i]}`;
};

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

onMounted(fetchFolders);

const columns = [
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
        width: '35%',

        customRender: ({ record }) => {
            const IconComponent = getFileIcon(record.name);

            return h(
                'div',
                {
                    class: 'flex items-center gap-2 min-w-0'
                },
                [
                    h(IconComponent, {
                        class: 'text-teal-500 text-lg flex-shrink-0'
                    }),

                    h(
                        'span',
                        {
                            class: 'font-medium text-gray-700 truncate',
                            title: record.name
                        },
                        record.name
                    )
                ]
            );
        }
    },
    {
    title: 'Type',
    key: 'file_type',
    width: '10%',

    customRender: ({ record }) => {
        const extension = record.name?.split('.').pop()?.toUpperCase() || 'FILE';

        return h(
            Tag,
            { color: 'teal' },
            {
                default: () => extension
            }
        );
    }
},

    {
        title: 'Size',
        key: 'size',
        width: '15%',

        customRender: ({ record }) => {
            return h(
                'span',
                {
                    class: 'text-gray-600'
                },
                formatFileSize(record.size)
            );
        }
    },

    {
        title: 'Date',
        key: 'created_at',
        width: '15%',

        customRender: ({ record }) => {
            return h(
                'span',
                {
                    class: 'text-gray-600'
                },
                formatDate(record.created_at)
            );
        }
    },

    {
        title: 'Actions',
        key: 'actions',
        width: '20%',
        align: 'center',

        customRender: ({ record }) => {
            return h(
                'div',
                {
                    class: 'flex gap-3 justify-center items-center whitespace-nowrap'
                },
                [

                    // PREVIEW
                    h(
                        Tooltip,
                        {
                            title: 'Preview File'
                        },
                        {
                            default: () =>
                                h(
                                    'a',
                                    {
                                        href: `/documents/${record.id}/preview`,
                                        target: '_blank',
                                        rel: 'noopener noreferrer',
                                        class: 'text-blue-600 hover:text-blue-700'
                                    },
                                    [
                                        h(EyeOutlined, {
                                            class: 'text-lg'
                                        })
                                    ]
                                )
                        }
                    ),

                    // DOWNLOAD
                    h(
                        Tooltip,
                        {
                            title: 'Download File'
                        },
                        {
                            default: () =>
                                h(
                                    'a',
                                    {
                                        href: `/documents/${record.id}/download`,
                                        class: 'text-teal-600 hover:text-teal-700'
                                    },
                                    [
                                        h(DownloadOutlined, {
                                            class: 'text-lg'
                                        })
                                    ]
                                )
                        }
                    ),

                    // DELETE
                    h(
                        Popconfirm,
                        {
                            title: 'Are you sure you want to delete this file?',
                            onConfirm: () => deleteFile(record.id),
                            okText: 'Yes',
                            cancelText: 'No',
                            okType: 'danger'
                        },
                        {
                            default: () =>
                                h(
                                    Tooltip,
                                    {
                                        title: 'Delete File'
                                    },
                                    {
                                        default: () =>
                                            h(
                                                Button,
                                                {
                                                    type: 'link',
                                                    danger: true,
                                                    size: 'small',
                                                    icon: h(DeleteOutlined),
                                                    class: 'p-0'
                                                }
                                            )
                                    }
                                )
                        }
                    )

                ]
            );
        }
    }
];

</script>

<template>
    <Head title="Document Repository" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Document Repositoryxx
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Header Section -->
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Document Repository</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Manage and organize your documents in folders
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Total: {{ folders.length }} Folders
                                </div>
                                <Button 
                                    type="primary" 
                                    @click="showCreateFolder = true"
                                    class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700"
                                >
                                    <PlusOutlined />
                                    New Folder
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- LEFT: FOLDERS SECTION -->
                            <div class="lg:col-span-4">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <img src="/assets/document.png" alt="Folder" class="w-5 h-5" />
                                            <h4 class="font-semibold text-gray-900">Folders</h4>
                                        </div>
                                        <Button 
                                            size="small" 
                                            @click="fetchFolders"
                                            :loading="loading"
                                            class="border-gray-200 hover:border-teal-500"
                                        >
                                            <ReloadOutlined />
                                        </Button>
                                    </div>

                                    <!-- Loading State -->
                                    <div v-if="loading" class="text-center py-8">
                                        <Spin size="small" />
                                    </div>

                                    <!-- Folders List -->
                                    <div v-else-if="folders.length > 0" class="space-y-2">
                                        <div 
                                            v-for="folder in folders" 
                                            :key="folder.id"
                                            @click="openFolder(folder)"
                                            :class="[
                                                'p-3 rounded-lg cursor-pointer transition-all duration-200 group',
                                                selectedFolder?.id === folder.id 
                                                    ? 'bg-gradient-to-r from-teal-50 to-emerald-50 border-l-4 border-teal-600 shadow-sm' 
                                                    : 'hover:bg-gray-100 border-l-4 border-transparent'
                                            ]"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <img 
                                                        src="/assets/document.png" 
                                                        alt="Folder" 
                                                        class="w-5 h-5"
                                                        :class="selectedFolder?.id === folder.id ? 'opacity-100' : 'opacity-70'"
                                                    />
                                                    <span :class="[
                                                        'font-medium',
                                                        selectedFolder?.id === folder.id ? 'text-teal-700' : 'text-gray-700'
                                                    ]">
                                                        {{ folder.name }}
                                                    </span>
                                                </div>
                                                <Tag :color="selectedFolder?.id === folder.id ? 'teal' : 'default'" class="text-xs">
                                                    {{ folder.documents?.length || 0 }} files
                                                </Tag>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Empty State -->
                                    <div v-else class="text-center py-8">
                                        <img src="/assets/document.png" alt="Empty folder" class="w-16 h-16 mx-auto mb-3 opacity-50" />
                                        <Empty description="No folders yet" />
                                        <Button 
                                            type="link" 
                                            @click="showCreateFolder = true"
                                            class="mt-2"
                                        >
                                            Create your first folder
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT: FILES SECTION -->
                            <div class="lg:col-span-8">
                                <div class="bg-gray-50 rounded-xl p-4 min-h-[500px]">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-2">
                                            <img src="/assets/document.png" alt="Folder" class="w-5 h-5" />
                                            <h4 class="font-semibold text-gray-900">
                                                {{ selectedFolder ? selectedFolder.name : 'Select a Folder' }}
                                            </h4>
                                        </div>
                                        <div v-if="selectedFolder" class="text-sm text-gray-500">
                                            {{ selectedFolder.documents?.length || 0 }} files
                                        </div>
                                    </div>

                                    <!-- No Folder Selected -->
                                    <div v-if="!selectedFolder" class="text-center py-16">
                                        <img src="/assets/document.png" alt="Select folder" class="w-20 h-20 mx-auto mb-4 opacity-50" />
                                        <p class="text-gray-500">Select a folder from the left to view its contents</p>
                                    </div>

                                    <!-- Upload Area -->
                                    <div v-else class="mb-6">
                                        <a-upload-dragger 
                                            name="file" 
                                            multiple 
                                            :showUploadList="false" 
                                            :customRequest="customRequest"
                                            class="upload-dragger"
                                        >
                                            <div class="py-8">
                                                <UploadOutlined class="text-4xl text-teal-400 mb-3" />
                                                <p class="ant-upload-text text-gray-700 font-medium">
                                                    Click or drag file to upload
                                                </p>
                                                <p class="ant-upload-hint text-gray-400 text-sm mt-1">
                                                    PDF, DOCX, XLSX, JPG, PNG supported
                                                </p>
                                            </div>
                                        </a-upload-dragger>
                                    </div>

                                    <!-- Files Table -->
                                    <div v-if="selectedFolder && selectedFolder.documents?.length > 0">
                                        <a-table 
                                            :dataSource="selectedFolder.documents" 
                                            :columns="columns" 
                                            rowKey="id"
                                            :scroll="{ x: 800 }"
                                            :pagination="{ 
                                                pageSize: 5, 
                                                showSizeChanger: true,
                                                showTotal: (total) => `Total ${total} files`
                                            }"
                                            size="middle"
                                            class="files-table"
                                        />
                                    </div>

                                    <!-- No Files State -->
                                    <div v-else-if="selectedFolder" class="text-center py-12">
                                        <img src="/assets/document.png" alt="Empty folder" class="w-16 h-16 mx-auto mb-3 opacity-50" />
                                        <p class="text-gray-500">No files in this folder</p>
                                        <p class="text-gray-400 text-sm mt-1">Upload files to get started</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Folder Modal -->
                <CreateDocument 
                    :open="showCreateFolder" 
                    @update:open="showCreateFolder = $event"
                    @created="fetchFolders" 
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Upload Dragger Styling */
:deep(.upload-dragger .ant-upload-drag) {
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    background: white;
    transition: all 0.3s ease;
}

:deep(.upload-dragger .ant-upload-drag:hover) {
    border-color: #14b8a6;
    background: #f0fdfa;
}

:deep(.upload-dragger .ant-upload-drag-icon) {
    margin-bottom: 0;
}

/* Table Styling */
:deep(.files-table .ant-table) {
    border-radius: 0.5rem;
    overflow: hidden;
}

:deep(.files-table .ant-table-thead > tr > th) {
    background-color: #f8fafc;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    border-bottom: 2px solid #e2e8f0;
}

:deep(.files-table .ant-table-tbody > tr:hover > td) {
    background-color: #f8fafc !important;
}

:deep(.files-table .ant-table-tbody > tr > td) {
    padding: 12px 16px;
}

/* Tag Styling */
:deep(.ant-tag) {
    border-radius: 1rem;
    padding: 2px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
}

/* Button Styling */
:deep(.ant-btn-primary) {
    background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
    border: none;
}

:deep(.ant-btn-primary:hover) {
    background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

/* Scrollbar Styling */
:deep(.ant-table-body) {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

:deep(.ant-table-body::-webkit-scrollbar) {
    width: 6px;
    height: 6px;
}

:deep(.ant-table-body::-webkit-scrollbar-track) {
    background: #f1f1f1;
    border-radius: 10px;
}

:deep(.ant-table-body::-webkit-scrollbar-thumb) {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .grid {
        gap: 1rem;
    }
    
    :deep(.files-table .ant-table) {
        font-size: 0.8125rem;
    }
    
    :deep(.files-table .ant-table-thead > tr > th) {
        padding: 8px 12px;
    }
    
    :deep(.files-table .ant-table-tbody > tr > td) {
        padding: 8px 12px;
    }
}
</style>
