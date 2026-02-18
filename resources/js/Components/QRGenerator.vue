<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">QR Code Generator</h1>
        
        <!-- Type Selection -->
        <div class="mb-6">
            <label class="block mb-2">Select QR Code Type:</label>
            <select v-model="selectedType" class="w-full p-2 border rounded">
                <option value="vcard">vCard (Contact)</option>
                <option value="email">Email</option>
                <option value="sms">SMS</option>
                <option value="url">URL</option>
                <option value="social">Social Media</option>
            </select>
        </div>

        <!-- Dynamic Form Component -->
        <component 
            :is="currentForm" 
            v-model:data="formData"
            class="mb-6"
        />

        <!-- Customization Options -->
        <div class="mb-6 p-4 border rounded">
            <h3 class="font-bold mb-4">Customization</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Size (px)</label>
                    <input type="range" v-model="settings.size" min="100" max="500" class="w-full">
                    <span>{{ settings.size }}px</span>
                </div>
                
                <div>
                    <label>Margin</label>
                    <input type="range" v-model="settings.margin" min="0" max="10" class="w-full">
                </div>
                
                <div>
                    <label>Color</label>
                    <input type="color" v-model="settings.color.hex" @change="updateColor">
                </div>
                
                <div>
                    <label>Background</label>
                    <input type="color" v-model="settings.background.hex" @change="updateBackground">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block mb-2">Logo (optional)</label>
                <input type="file" @change="handleLogoUpload" accept="image/*">
            </div>
        </div>

        <!-- Generate Button -->
        <button 
            @click="generateQR" 
            class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700"
            :disabled="loading"
        >
            {{ loading ? 'Generating...' : 'Generate QR Code' }}
        </button>

        <!-- Result -->
        <div v-if="qrImage" class="mt-6 text-center">
            <h3 class="font-bold mb-4">Your QR Code:</h3>
            <img :src="qrImage" class="mx-auto mb-4">
            <a :href="qrImage" download class="bg-green-600 text-white px-6 py-2 rounded">
                Download
            </a>
        </div>
    </div>
</template>

<script>
import VCardForm from './forms/VCardForm.vue';
import EmailForm from './forms/EmailForm.vue';
import SMSForm from './forms/SMSForm.vue';
import URLForm from './forms/URLForm.vue';
import SocialForm from './forms/SocialForm.vue';

export default {
    components: {
        VCardForm,
        EmailForm,
        SMSForm,
        URLForm,
        SocialForm
    },
    
    data() {
        return {
            selectedType: 'vcard',
            formData: {},
            settings: {
                size: 300,
                margin: 1,
                color: { hex: '#000000', r: 0, g: 0, b: 0 },
                background: { hex: '#ffffff', r: 255, g: 255, b: 255 },
                logo: null
            },
            loading: false,
            qrImage: null
        }
    },
    
    computed: {
        currentForm() {
            const forms = {
                vcard: 'VCardForm',
                email: 'EmailForm',
                sms: 'SMSForm',
                url: 'URLForm',
                social: 'SocialForm'
            };
            return forms[this.selectedType];
        }
    },
    
    methods: {
        updateColor() {
            const hex = this.settings.color.hex;
            this.settings.color = {
                ...this.settings.color,
                ...this.hexToRgb(hex)
            };
        },
        
        updateBackground() {
            const hex = this.settings.background.hex;
            this.settings.background = {
                ...this.settings.background,
                ...this.hexToRgb(hex)
            };
        },
        
        hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },
        
        handleLogoUpload(event) {
            this.settings.logo = event.target.files[0];
        },
        
        // async generateQR() {
        //     this.loading = true;
            
        //     const formData = new FormData();
        //     formData.append('type', this.selectedType);
        //     formData.append('data', JSON.stringify(this.formData));
        //     formData.append('settings', JSON.stringify(this.settings));
            
        //     if (this.settings.logo) {
        //         formData.append('logo', this.settings.logo);
        //     }
            
        //     try {
        //         const response = await axios.post('/api/qr/generate', formData);
        //         this.qrImage = response.data.file;
        //     } catch (error) {
        //         console.error('Generation failed:', error);
        //     } finally {
        //         this.loading = false;
        //     }
        // }

        // In your QRGenerator.vue component
async generateQR() {
    this.loading = true;
    
    // Create FormData object
    const formData = new FormData();
    formData.append('type', this.selectedType);
    
    // Ensure formData is properly structured
    formData.append('data', JSON.stringify(this.formData));
    formData.append('settings', JSON.stringify(this.settings));
    
    if (this.settings.logo) {
        formData.append('logo', this.settings.logo);
    }
    
    try {
        const response = await axios.post('/api/qr/generate', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        this.qrImage = response.data.file;
    } catch (error) {
        console.error('Generation failed:', error);
        // Show user-friendly error message
        alert('Failed to generate QR code. Please check all fields are filled correctly.');
    } finally {
        this.loading = false;
    }
}
    }
}
</script>