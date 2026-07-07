import { ref, onMounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';

// Configure Axios defaults for session authentication
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = window.location.origin;

const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const isSidebarCollapsed = ref(false);

export function useAppState() {
    const page = usePage();

    const currentUser = computed(() => page.props.auth?.user || null);
    const isAuthenticated = computed(() => !!page.props.auth?.user);
    const isLoading = ref(false);
    const currentUserRole = computed(() => {
        const roles = page.props.auth?.user?.roles || [];
        return roles[0] || 'staff';
    });

    onMounted(() => {
        if (typeof window !== 'undefined') {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                isDark.value = true;
                document.documentElement.classList.add('dark');
            } else {
                isDark.value = false;
                document.documentElement.classList.remove('dark');
            }
            
            const savedSidebar = localStorage.getItem('sidebarCollapsed');
            if (savedSidebar === 'true') {
                isSidebarCollapsed.value = true;
            }
        }
    });

    const toggleDarkMode = () => {
        isDark.value = !isDark.value;
        if (isDark.value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    const toggleMobileMenu = () => {
        isMobileMenuOpen.value = !isMobileMenuOpen.value;
    };

    const toggleSidebar = () => {
        isSidebarCollapsed.value = !isSidebarCollapsed.value;
        if (typeof window !== 'undefined') {
            localStorage.setItem('sidebarCollapsed', isSidebarCollapsed.value.toString());
        }
    };

    const closeMobileMenu = () => {
        isMobileMenuOpen.value = false;
    };

    // Perform Laravel Session Login via Inertia
    const login = async (email, password, remember = false) => {
        router.post('/login', {
            email,
            password,
            remember
        });
    };

    // Perform Laravel Session Logout via Inertia
    const logout = async () => {
        router.post('/logout');
    };

    // Register a new user and company via Inertia
    const signup = async (name, email, password, password_confirmation, companyName) => {
        router.post('/register', {
            name,
            email,
            password,
            password_confirmation,
            company_name: companyName
        });
    };

    return {
        isDark,
        isMobileMenuOpen,
        isSidebarCollapsed,
        currentUser,
        isAuthenticated,
        isLoading,
        currentUserRole,
        toggleDarkMode,
        toggleMobileMenu,
        toggleSidebar,
        closeMobileMenu,
        login,
        logout,
        signup,
        checkAuth: () => {} // Stubbed since Inertia hydrates natively
    };
}
