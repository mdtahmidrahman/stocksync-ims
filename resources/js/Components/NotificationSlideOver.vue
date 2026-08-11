<template>
  <div>
    <!-- Backdrop Overlay -->
    <Transition
      enter-active-class="transition-opacity ease-linear duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity ease-linear duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="isOpen" 
        class="fixed inset-0 bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm z-40"
        @click="close"
      ></div>
    </Transition>

    <!-- Slide-over Panel -->
    <Transition
      enter-active-class="transform transition ease-in-out duration-300 sm:duration-500"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transform transition ease-in-out duration-300 sm:duration-500"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div 
        v-if="isOpen" 
        class="fixed inset-y-0 right-0 w-full max-w-sm bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-l border-gray-200 dark:border-gray-800 shadow-2xl z-50 flex flex-col overflow-hidden"
      >
        <!-- Header -->
        <div class="px-4 py-6 sm:px-6 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              Notifications
              <span v-if="unreadCount > 0" class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary-600 rounded-full">
                {{ unreadCount }}
              </span>
            </h2>
            <div class="flex items-center gap-3">
              <button 
                v-if="unreadCount > 0"
                @click="markAllAsRead" 
                class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors"
              >
                Mark all read
              </button>
              <button 
                @click="close"
                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg p-1 transition-colors"
              >
                <span class="sr-only">Close panel</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
          <div class="mt-2 flex items-center justify-between">
            <button 
              v-if="permissionStatus === 'default'" 
              @click="requestPermission" 
              class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline"
            >
              Enable Browser Alerts
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 relative">
          <!-- Loading State -->
          <div v-if="isLoading" class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 flex justify-center items-center backdrop-blur-sm z-10">
            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Empty State -->
          <div v-if="!isLoading && notifications.length === 0" class="flex flex-col items-center justify-center h-full text-center space-y-3">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">You're all caught up!<br>No new notifications.</p>
          </div>

          <!-- Notification List -->
          <div v-for="notification in notifications" :key="notification.id" 
               class="group relative rounded-xl p-4 transition-all duration-200 border"
               :class="[
                 notification.read_at === null 
                   ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-100 dark:border-primary-800/50 shadow-sm' 
                   : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 shadow-sm'
               ]"
          >
            <!-- Unread Indicator -->
            <div v-if="notification.read_at === null" class="absolute top-4 right-4 w-2 h-2 bg-primary-600 rounded-full"></div>

            <div class="flex items-start gap-3">
              <!-- Thumbnail Image or StockSync Logo Fallback -->
              <div class="flex-shrink-0 w-11 h-11 rounded-lg overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 relative">
                <img 
                  v-if="notification.data.image" 
                  :src="getResolvedImage(notification.data.image)" 
                  class="w-full h-full object-cover" 
                  @error="onImageError($event, notification.id)"
                />
                <!-- Official StockSync Logo / Type Icon Fallback -->
                <div v-if="!notification.data.image || imageFailed[notification.id]" class="w-full h-full flex items-center justify-center" :class="getIconWrapperClass(notification.data.type || 'info')">
                  <component :is="getIcon(notification.data.type || 'info')" class="w-5 h-5" :class="getIconClass(notification.data.type || 'info')" />
                </div>
              </div>
              
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                  {{ notification.data.title || 'Notification' }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">
                  {{ notification.data.message }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ formatDate(notification.created_at) }}
                </p>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-3 flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
              <button 
                v-if="notification.read_at === null"
                @click="markAsRead(notification.id)" 
                class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors"
              >
                Mark read
              </button>
              <button 
                @click="deleteNotification(notification.id)"
                class="text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-700 transition-colors"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  isOpen: Boolean,
});

const emit = defineEmits(['update:isOpen', 'update:unreadCount']);

const notifications = ref([]);
const unreadCount = ref(0);
const isLoading = ref(false);
const imageFailed = ref({});
const permissionStatus = ref('default');

const prevUnreadCount = ref(null);
const knownIds = ref(new Set());
let pollInterval = null;

const close = () => {
  emit('update:isOpen', false);
};

// Image resolution helper
const getResolvedImage = (path) => {
  if (!path) return '';
  return path.startsWith('http') ? path : `/storage/${path}`;
};

const onImageError = (e, id) => {
  imageFailed.value[id] = true;
};

// Play Audio Chime Sound (Web Audio API Synthesizer)
const playChimeSound = () => {
  try {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (!AudioContext) return;
    const ctx = new AudioContext();

    const now = ctx.currentTime;
    
    // Tone 1: E5
    const osc1 = ctx.createOscillator();
    const gain1 = ctx.createGain();
    osc1.type = 'sine';
    osc1.frequency.setValueAtTime(659.25, now);
    gain1.gain.setValueAtTime(0.15, now);
    gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
    osc1.connect(gain1);
    gain1.connect(ctx.destination);
    osc1.start(now);
    osc1.stop(now + 0.3);

    // Tone 2: A5
    const osc2 = ctx.createOscillator();
    const gain2 = ctx.createGain();
    osc2.type = 'sine';
    osc2.frequency.setValueAtTime(880, now + 0.15);
    gain2.gain.setValueAtTime(0.2, now + 0.15);
    gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
    osc2.connect(gain2);
    gain2.connect(ctx.destination);
    osc2.start(now + 0.15);
    osc2.stop(now + 0.55);
  } catch (err) {
    console.log('Audio chime error:', err);
  }
};

// HTML5 Desktop Notification
const requestPermission = () => {
  if ('Notification' in window) {
    Notification.requestPermission().then((res) => {
      permissionStatus.value = res;
    });
  }
};

const triggerDesktopAlert = (title, message, image) => {
  if ('Notification' in window && Notification.permission === 'granted') {
    try {
      new Notification(title, {
        body: message,
        icon: image ? getResolvedImage(image) : '/favicon.ico'
      });
    } catch (e) {
      console.log('Desktop alert error:', e);
    }
  }
};

// Fetch notifications with Real-time triggers
const fetchNotifications = async (showLoading = false) => {
  if (showLoading) isLoading.value = true;
  try {
    const response = await axios.get('/notifications');
    const data = response.data;
    const newNotifications = data.notifications || [];
    const newUnreadCount = data.unread_count || 0;

    let hasFreshAlert = false;

    // Check for newly arrived unread notifications
    newNotifications.forEach(n => {
      if (n.read_at === null && !knownIds.value.has(n.id)) {
        hasFreshAlert = true;
        if (prevUnreadCount.value !== null) {
          triggerDesktopAlert(
            n.data.title || 'StockSync Notification', 
            n.data.message || '', 
            n.data.image
          );
        }
      }
      knownIds.value.add(n.id);
    });

    if (hasFreshAlert && prevUnreadCount.value !== null) {
      playChimeSound();
    }

    notifications.value = newNotifications;
    unreadCount.value = newUnreadCount;
    prevUnreadCount.value = newUnreadCount;
    emit('update:unreadCount', unreadCount.value);
  } catch (error) {
    console.error('Failed to fetch notifications:', error);
  } finally {
    if (showLoading) isLoading.value = false;
  }
};

// Mark as read
const markAsRead = async (id) => {
  try {
    // Optimistic update
    const notif = notifications.value.find(n => n.id === id);
    if (notif && notif.read_at === null) {
      notif.read_at = new Date().toISOString();
      unreadCount.value = Math.max(0, unreadCount.value - 1);
      emit('update:unreadCount', unreadCount.value);
    }
    await axios.post(`/notifications/${id}/mark-read`);
  } catch (error) {
    console.error('Mark read error:', error);
  }
};

// Mark all as read
const markAllAsRead = async () => {
  try {
    // Optimistic update
    notifications.value.forEach(n => {
      if (n.read_at === null) n.read_at = new Date().toISOString();
    });
    unreadCount.value = 0;
    emit('update:unreadCount', 0);
    await axios.post(`/notifications/mark-all-read`);
  } catch (error) {
    console.error('Mark all read error:', error);
  }
};

// Delete
const deleteNotification = async (id) => {
  try {
    // Optimistic update
    const notif = notifications.value.find(n => n.id === id);
    if (notif && notif.read_at === null) {
      unreadCount.value = Math.max(0, unreadCount.value - 1);
      emit('update:unreadCount', unreadCount.value);
    }
    notifications.value = notifications.value.filter(n => n.id !== id);
    await axios.delete(`/notifications/${id}`);
  } catch (error) {
    console.error('Delete notification error:', error);
  }
};

// Watch panel open
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    fetchNotifications(true);
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

// Real-time 5-second polling
onMounted(() => {
  if ('Notification' in window) {
    permissionStatus.value = Notification.permission;
  }
  fetchNotifications(false);
  pollInterval = setInterval(() => {
    fetchNotifications(false);
  }, 5000); // 5 seconds polling for fast real-time response
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
  document.body.style.overflow = '';
});

// Relative time helper
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const diffInDays = Math.round((date.getTime() - Date.now()) / (1000 * 60 * 60 * 24));
  if (diffInDays === 0) return 'today';
  return new Intl.RelativeTimeFormat('en', { numeric: 'auto' }).format(diffInDays, 'day');
};

// Type icons
const AlertIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>' };
const SuccessIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' };
const InfoIcon = { template: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' };

const getIcon = (type) => {
  switch (type) {
    case 'alert':
    case 'warning': return AlertIcon;
    case 'success': return SuccessIcon;
    default: return InfoIcon;
  }
};

const getIconWrapperClass = (type) => {
  switch (type) {
    case 'alert':
    case 'warning': return 'bg-amber-100 dark:bg-amber-900/40';
    case 'success': return 'bg-emerald-100 dark:bg-emerald-900/40';
    default: return 'bg-blue-100 dark:bg-blue-900/40';
  }
};

const getIconClass = (type) => {
  switch (type) {
    case 'alert':
    case 'warning': return 'text-amber-600 dark:text-amber-400';
    case 'success': return 'text-emerald-600 dark:text-emerald-400';
    default: return 'text-blue-600 dark:text-blue-400';
  }
};
</script>
