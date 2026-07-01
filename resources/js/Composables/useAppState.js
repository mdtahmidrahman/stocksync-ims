import { ref, onMounted } from 'vue';
import axios from 'axios';

// Configure Axios defaults for session authentication
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = window.location.origin;

const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const isSidebarCollapsed = ref(false);
const currentUser = ref(null);
const isAuthenticated = ref(false);
const isLoading = ref(true);
const currentUserRole = ref('staff'); // admin, manager, staff

// Fetch the authenticated user from the backend
const checkAuth = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/user');
        currentUser.value = response.data;
        isAuthenticated.value = true;
        currentUserRole.value = response.data.role || 'staff';
    } catch (error) {
        currentUser.value = null;
        isAuthenticated.value = false;
        currentUserRole.value = 'staff';
    } finally {
        isLoading.value = false;
    }
};

export function useAppState() {
    onMounted(async () => {
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

            // Run initial auth check
            await checkAuth();
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

    // Perform Laravel Session Login
    const login = async (email, password, remember = false) => {
        // Ensure CSRF Cookie is initialized
        await axios.get('/sanctum/csrf-cookie');
        
        await axios.post('/login', {
            email,
            password,
            remember
        });

        // Fetch User details
        await checkAuth();
    };

    // Perform Laravel Session Logout
    const logout = async () => {
        await axios.post('/logout');
        currentUser.value = null;
        isAuthenticated.value = false;
        currentUserRole.value = 'staff';
    };

    // Register a new user and company
    const signup = async (name, email, password, password_confirmation, companyName) => {
        await axios.get('/sanctum/csrf-cookie');

        await axios.post('/register', {
            name,
            email,
            password,
            password_confirmation,
            company_name: companyName
        });

        await checkAuth();
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
        checkAuth
    };
}
