import { ref, watch, onMounted } from 'vue';

const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const isSidebarCollapsed = ref(false);
const currentUserRole = ref('admin'); // admin, manager, staff

export function useAppState() {
    onMounted(() => {
        // Only run on client
        if (typeof window !== 'undefined') {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                isDark.value = true;
                document.documentElement.classList.add('dark');
            } else {
                isDark.value = false;
                document.documentElement.classList.remove('dark');
            }
            
            const savedRole = localStorage.getItem('role');
            if (savedRole) {
                currentUserRole.value = savedRole;
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

    const loginAs = (role) => {
        currentUserRole.value = role;
        if (typeof window !== 'undefined') {
            localStorage.setItem('role', role);
        }
    };

    const closeMobileMenu = () => {
        isMobileMenuOpen.value = false;
    };

    return {
        isDark,
        isMobileMenuOpen,
        isSidebarCollapsed,
        currentUserRole,
        toggleDarkMode,
        toggleMobileMenu,
        toggleSidebar,
        closeMobileMenu,
        loginAs
    };
}
