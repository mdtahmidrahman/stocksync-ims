import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useCurrency() {
    const page = usePage();
    
    const currencySymbol = computed(() => {
        const currencySetting = page.props.auth.company?.currency || 'USD ($)';
        const match = currencySetting.match(/\((.*?)\)/);
        return match ? match[1] : '$';
    });

    return { currencySymbol };
}
