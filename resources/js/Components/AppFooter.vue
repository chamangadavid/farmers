<!-- Resources/js/Components/AppFooter.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-gradient-to-b from-teal-900 to-teal-950 text-white pt-16 pb-8">
        <!-- Newsletter Section -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h3
                    class="text-2xl font-bold mb-2 bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">
                    Keep up to date with ALINASWE FARM Speciallized News
                </h3>

                <form class="mt-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" placeholder="First name"
                            class="px-4 py-3 bg-white/10 border border-teal-500/30 rounded-lg focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-500/20 text-white placeholder-white/50 transition-all" />
                        <input type="text" placeholder="Last name"
                            class="px-4 py-3 bg-white/10 border border-teal-500/30 rounded-lg focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-500/20 text-white placeholder-white/50 transition-all" />
                    </div>

                    <div class="relative">
                        <input type="email" placeholder="Email address *" required
                            class="w-full px-4 py-3 bg-white/10 border border-teal-500/30 rounded-lg focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-500/20 text-white placeholder-white/50 transition-all" />
                    </div>

                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="newsletter"
                            class="mt-1 w-4 h-4 text-teal-600 bg-white/10 border-teal-500/30 rounded focus:ring-teal-500 focus:ring-2" />
                        <label for="newsletter" class="text-sm text-white/70 text-left">
                            Subscribe me to the ALINASWE FARM newsletter for articles, events, and offers.
                        </label>
                    </div>

                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-teal-500/30">
                        Subscribe
                    </button>
                </form>
            </div>

            <!-- Footer Links Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8 mt-12">
                <!-- Expertise Column -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-teal-400">Quick Links</h4>
                    <ul class="space-y-2">
                           <li>
                            <Link :href="route('aboutUs')"
                                class="text-white/60 hover:text-teal-400 transition-colors text-sm block">About Us
                            </Link>
                        </li>

                           <li>
                            <Link :href="route('aboutUs')"
                                class="text-white/60 hover:text-teal-400 transition-colors text-sm block">Our Services
                            </Link>
                        </li>

                           <li>
                            <Link :href="route('aboutUs')"
                                class="text-white/60 hover:text-teal-400 transition-colors text-sm block">Vegetables
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Company Column -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-teal-400">Our Farm</h4>
                    <ul class="space-y-2">
                        <li>
                            <Link :href="route('newsPage')"
                                class="text-white/60 hover:text-teal-400 transition-colors text-sm block">Specialized News
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('contactDetails')"
                                class="text-white/60 hover:text-teal-400 transition-colors text-sm block">Contact Us
                            </Link>
                        </li>
                         <li>
                            <Link href="#" class="text-white/60 hover:text-teal-400 transition-colors text-sm block">
                                Chickens</Link>
                        </li>
                    </ul>
                </div>

                <!-- Learn & Support Column -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-teal-400">Report & Support</h4>
                    <ul class="space-y-2">
                        <li>
                            <Link href="#" class="text-white/60 hover:text-teal-400 transition-colors text-sm block">
                                (+260) 976-535-424 </Link>
                        </li>
                        <li>
                            <Link href="#" class="text-white/60 hover:text-teal-400 transition-colors text-sm block">
                                info@alinaswe.com || www.alinaswefarm.com</Link>
                        </li>
                        <li>
                            <Link href="#" class="text-white/60 hover:text-teal-400 transition-colors text-sm block">KK
                                Plot A1855 Kwamwena Meanwood Lusaka, 10101 Zambia</Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-teal-500/30 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Company Name and Copyright -->
                    <div class="text-center md:text-left">
                        <div class="flex items-center gap-2 justify-center md:justify-start mb-2">
                            <img src="/assets/marz-logo.png" alt="AAIB" class="h-8 w-auto" />
                            <span
                                class="text-lg font-bold bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">
                                ALINASWE FARM
                            </span>
                        </div>
                        <p class="text-white/50 text-sm">
                            © 2026 Alinaswe Farm. All rights reserved.
                        </p>
                    </div>

                    <!-- Social Media Icons -->
                    <div class="flex space-x-4">
                        <a href="#" class="text-white/50 hover:text-teal-400 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.847c-4.013 0-4.433 2.379-4.433 4.267v2.733z" />
                            </svg>
                        </a>
                        <a href="#" class="text-white/50 hover:text-teal-400 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.103 0-5.597 2.838-4.993 5.787-4.143-.207-7.822-2.185-10.285-5.181-.426.72-.666 1.56-.666 2.47 0 1.637.833 3.067 2.091 3.905-.77-.024-1.49-.234-2.11-.583v.03c0 3.067 2.202 5.617 5.102 6.182-.537.146-1.106.223-1.69.223-.412 0-.81-.04-1.197-.115.811 2.544 3.167 4.385 5.955 4.436-2.198 1.727-4.974 2.766-8 2.766-.525 0-1.04-.03-1.547-.09 2.858 1.834 6.237 2.909 9.893 2.909 11.854 0 18.332-9.884 18.332-18.514 0-.281-.006-.562-.019-.842.8-.577 1.493-1.299 2.046-2.124z" />
                            </svg>
                        </a>
                        <a href="#" class="text-white/50 hover:text-teal-400 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-.01 17c-2.761 0-5-2.239-5-5s2.239-5 5-5 5 2.239 5 5-2.239 5-5 5z" />
                            </svg>
                        </a>
                        <a href="#" class="text-white/50 hover:text-teal-400 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.529-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
/* Smooth transitions */
a,
button {
    transition: all 0.2s ease;
}

/* Custom scrollbar for consistency */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: rgba(20, 184, 166, 0.5);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(20, 184, 166, 0.7);
}

/* Input focus effects */
input:focus {
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Newsletter checkbox styling */
input[type="checkbox"] {
    accent-color: #14b8a6;
}

/* Ensure links are block-level for better click area */
ul li a {
    display: block;
}
</style>