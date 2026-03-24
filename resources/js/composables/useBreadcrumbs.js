// Resources/js/composables/useBreadcrumbs.js
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useBreadcrumbs() {
    const page = usePage();
    const currentUrl = computed(() => page.url);
    
    const getBreadcrumbs = (currentRoute) => {
        const breadcrumbMap = {
            // About section
            'about-us': [
                { label: 'About Us' }
            ],
            'management-team': [
                { label: 'About', route: '/about-us' },
                { label: 'Management Team' }
            ],
            'mandates': [
                { label: 'About', route: '/about-us' },
                { label: 'Mandate' }
            ],
            'history-details': [
                { label: 'About', route: '/about-us' },
                { label: 'History' }
            ],
            
            // Accidents section
            'icao-annex': [
                { label: 'Accidents', route: '/accidents' },
                { label: 'ICAO Annex 13' }
            ],
            'national-regulation': [
                { label: 'Accidents', route: '/accidents' },
                { label: 'National Regulation' }
            ],
            'investigation-process': [
                { label: 'Accidents', route: '/accidents' },
                { label: 'Investigation Process' }
            ],
            'accident-reports': [
                { label: 'Accidents', route: '/accidents' },
                { label: 'Accident Reports' }
            ],
            
            // Media section
            'news': [
                { label: 'Media', route: '/media' },
                { label: 'News' }
            ],
            'press-releases': [
                { label: 'Media', route: '/media' },
                { label: 'Press Releases' }
            ],
            'announcements': [
                { label: 'Media', route: '/media' },
                { label: 'Announcements' }
            ],
            
            // Other pages
            'job-vacancies': [
                { label: 'Job Vacancies' }
            ],
            'report-accident': [
                { label: 'Report Accident' }
            ],
            'faq': [
                { label: 'FAQs' }
            ],
            'contact-us': [
                { label: 'Contact Us' }
            ],
        };
        
        return breadcrumbMap[currentRoute] || [{ label: 'Home' }];
    };
    
    const currentBreadcrumbs = computed(() => {
        // Get the route name from the URL
        const path = currentUrl.value;
        const routeName = path.split('/').filter(Boolean).pop() || '';
        
        // Handle nested routes
        if (path.includes('/about/')) {
            const aboutPage = path.split('/').pop();
            if (aboutPage === 'management-team') return getBreadcrumbs('management-team');
            if (aboutPage === 'mandates') return getBreadcrumbs('mandates');
            if (aboutPage === 'history-details') return getBreadcrumbs('history-details');
            return getBreadcrumbs('about-us');
        }
        
        if (path.includes('/accidents/')) {
            const accidentPage = path.split('/').pop();
            if (accidentPage === 'icao-annex') return getBreadcrumbs('icao-annex');
            if (accidentPage === 'national-regulation') return getBreadcrumbs('national-regulation');
            if (accidentPage === 'investigation-process') return getBreadcrumbs('investigation-process');
            if (accidentPage === 'accident-reports') return getBreadcrumbs('accident-reports');
            return getBreadcrumbs('accidents');
        }
        
        if (path.includes('/media/')) {
            const mediaPage = path.split('/').pop();
            if (mediaPage === 'news') return getBreadcrumbs('news');
            if (mediaPage === 'press-releases') return getBreadcrumbs('press-releases');
            if (mediaPage === 'announcements') return getBreadcrumbs('announcements');
            return getBreadcrumbs('media');
        }
        
        // Direct routes
        return getBreadcrumbs(routeName) || [{ label: 'Home' }];
    });
    
    return {
        breadcrumbs: currentBreadcrumbs
    };
}