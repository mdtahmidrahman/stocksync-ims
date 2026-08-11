<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Support & Help Center</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Get immediate assistance, track support tickets, or search the FAQ database.</p>
      </div>
      <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold shrink-0">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        Support Desk Online • Avg SLA < 15 mins
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left Column: Create Ticket & Recent Tickets List -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Contact Form Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Create Support Ticket</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Need help with the system? Reach out to the admin team.</p>
          
          <form @submit.prevent="submitTicket" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Issue Category</label>
              <select v-model="form.category" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="System Bug">System Bug</option>
                <option value="Inventory Discrepancy">Inventory Discrepancy</option>
                <option value="POS / Checkout">POS / Checkout</option>
                <option value="Billing Issue">Billing Issue</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority Level</label>
              <select v-model="form.priority" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="low">Low - Normal Request</option>
                <option value="medium">Medium - System Question</option>
                <option value="high">High - Operational Blocker</option>
                <option value="urgent">Urgent - Outage / Critical</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
              <input v-model="form.subject" type="text" required placeholder="Brief summary of the issue..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
              <textarea v-model="form.message" rows="4" required placeholder="Detailed explanation..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm"></textarea>
            </div>
            <button type="submit" :disabled="form.processing" class="w-full bg-primary-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50">
              {{ form.processing ? 'Submitting Ticket...' : 'Submit Support Ticket' }}
            </button>
          </form>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Tickets</h2>
            <span class="text-xs font-semibold text-gray-500">{{ tickets.length }} Total</span>
          </div>

          <div class="space-y-3">
            <div v-if="!tickets || tickets.length === 0" class="text-xs text-gray-500 dark:text-gray-400 text-center py-6 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl">
              No support tickets submitted yet.
            </div>
            
            <div 
              v-for="ticket in tickets" 
              :key="ticket.id" 
              @click="openTicketDetails(ticket)"
              class="border border-gray-100 dark:border-gray-800 rounded-xl p-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer group"
            >
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate pr-2">{{ ticket.subject }}</span>
                <span :class="[
                  'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize shrink-0',
                  ticket.status === 'open' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' :
                  ticket.status === 'in_progress' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' :
                  ticket.status === 'resolved' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' :
                  'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300'
                ]">{{ ticket.status?.replace('_', ' ') }}</span>
              </div>
              <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>#{{ ticket.id }} • {{ ticket.category }}</span>
                <span>{{ formatDate(ticket.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: FAQs & Direct Assistance -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Direct Assistance Banner -->
        <div class="bg-gradient-to-r from-primary-900 to-slate-900 rounded-xl p-6 text-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
          <div>
            <h3 class="text-lg font-bold text-white">Need Urgent System Assistance?</h3>
            <p class="text-primary-100 text-sm mt-1">If you are facing a critical issue, our support team can help.</p>
          </div>
          <div class="flex items-center gap-3 shrink-0">
             <a href="mailto:support@stocksync.com" class="px-5 py-2.5 bg-white text-primary-900 rounded-lg text-sm font-bold hover:bg-gray-50 transition-colors shadow-sm">
                Email Support Team
             </a>
          </div>
        </div>

        <!-- Allow Support Impersonation Toggle (For Admins) -->
        <div v-if="$page.props.auth.user?.roles?.includes('admin')" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center justify-between transition-colors">
          <div>
            <h3 class="text-md font-bold text-gray-900 dark:text-white">Allow Support Impersonation</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Grant our platform support team temporary access to log into your account to troubleshoot issues.</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
            <input type="checkbox" :checked="$page.props.auth.company?.allow_support_impersonation" @change="toggleImpersonation" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-600 peer-checked:bg-primary-600"></div>
          </label>
        </div>

        <!-- FAQs Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-lg font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h2>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Quick answers to common system operations.</p>
            </div>
            <div class="relative w-full sm:w-64">
              <input type="text" v-model="faqQuery" placeholder="Search FAQ topics..." class="w-full pl-9 pr-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white text-xs focus:ring-primary-500" />
              <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
          </div>
          
          <div class="divide-y divide-gray-100 dark:divide-gray-800">
            <div 
              v-for="(faq, i) in filteredFaqs" 
              :key="i" 
              class="p-6 cursor-pointer hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors"
              @click="toggleFaq(i)"
            >
              <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white pr-4">{{ faq.q }}</h3>
                <svg :class="[expandedFaq === i ? 'rotate-180 text-primary-600' : 'text-gray-400', 'w-5 h-5 transition-transform shrink-0']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </div>
              <p v-if="expandedFaq === i" class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                {{ faq.a }}
              </p>
            </div>
            <div v-if="filteredFaqs.length === 0" class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
              No matching FAQs found for "{{ faqQuery }}".
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Support Ticket Details & Reply Modal -->
    <Modal :show="showDetailsModal" :scrollable="true" @close="showDetailsModal = false">
      <template #title>
        <div class="flex items-center gap-2">
          <span>Support Ticket #{{ selectedTicket?.id }}</span>
          <span :class="[
            'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize',
            selectedTicket?.status === 'open' ? 'bg-yellow-100 text-yellow-800' :
            selectedTicket?.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
            selectedTicket?.status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
          ]">{{ selectedTicket?.status?.replace('_', ' ') }}</span>
        </div>
      </template>
      <template #body>
        <div v-if="selectedTicket" class="space-y-6">
          <!-- Ticket Header Details -->
          <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 space-y-2">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ selectedTicket.subject }}</h3>
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400">
              <div><strong>Category:</strong> {{ selectedTicket.category }}</div>
              <div><strong>Priority:</strong> <span class="uppercase font-semibold text-primary-600">{{ selectedTicket.priority || 'medium' }}</span></div>
              <div><strong>Submitted By:</strong> {{ selectedTicket.user?.name || 'User' }}</div>
              <div><strong>Date:</strong> {{ formatDate(selectedTicket.created_at) }}</div>
            </div>
          </div>

          <!-- Original Message -->
          <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase">Original Inquiry</div>
            <div class="p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line">
              {{ selectedTicket.message }}
            </div>
          </div>

          <!-- Conversation Thread Replies -->
          <div class="space-y-3">
            <div class="text-xs font-semibold text-gray-500 uppercase flex items-center justify-between">
              <span>Discussion Thread ({{ selectedTicket.replies?.length || 0 }} replies)</span>
            </div>

            <div v-for="reply in selectedTicket.replies" :key="reply.id" class="p-3.5 bg-gray-50 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 rounded-xl text-sm space-y-1">
              <div class="flex items-center justify-between text-xs font-semibold text-gray-900 dark:text-white">
                <span>{{ reply.user?.name || 'Support Agent' }}</span>
                <span class="text-gray-400 font-normal">{{ formatDate(reply.created_at) }}</span>
              </div>
              <p class="text-gray-700 dark:text-gray-300 text-xs whitespace-pre-line mt-1">{{ reply.message }}</p>
            </div>

            <div v-if="!selectedTicket.replies || selectedTicket.replies.length === 0" class="text-xs text-gray-400 italic">
              No replies yet. Type a response below to start the thread.
            </div>
          </div>

          <!-- Submit Reply Box -->
          <div class="space-y-2 pt-2 border-t border-gray-100 dark:border-gray-800">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Add Reply / Update Note</label>
            <textarea v-model="replyMessage" rows="3" placeholder="Write your response or update..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 text-sm"></textarea>
            <div class="flex items-center justify-between gap-2">
              <select v-model="updateStatusValue" class="px-2.5 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-black text-gray-900 dark:text-white">
                <option value="open">Status: Open</option>
                <option value="in_progress">Status: In Progress</option>
                <option value="resolved">Status: Resolved</option>
                <option value="closed">Status: Closed</option>
              </select>
              <button @click="submitReply" :disabled="isSubmittingReply || !replyMessage" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs rounded-xl shadow-sm disabled:opacity-50 transition-colors">
                {{ isSubmittingReply ? 'Sending...' : 'Send Reply' }}
              </button>
            </div>
          </div>
        </div>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { formatDate } from '../Composables/useDate';

const props = defineProps({
  tickets: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  subject: '',
  category: 'System Bug',
  priority: 'medium',
  message: '',
});

const submitTicket = () => {
  form.post('/support', {
    onSuccess: () => {
      form.reset('subject', 'message');
    },
  });
};

const expandedFaq = ref(0);
const faqQuery = ref('');

const toggleFaq = (index) => {
  expandedFaq.value = expandedFaq.value === index ? null : index;
};

const faqs = [
    { q: 'How do I transfer stock between warehouses?', a: 'Navigate to the Warehouses page, click "New Transfer", select the source and destination warehouses, select the products and quantities, and submit. The transfer will immediately update the pivot stock totals.' },
    { q: 'Can I export inventory & sales reports?', a: 'Yes! Navigate to the Reports or Products page and click the "Export CSV" button to download complete data tables for Excel or Google Sheets analysis.' },
    { q: 'What happens when an item reaches its Low Stock threshold?', a: 'The system automatically triggers a real-time notification with audio chime and browser desktop alerts, flagging the item in low stock widgets across the dashboard.' },
    { q: 'How do I assign location access to team members?', a: 'As a Company Admin, go to the Team / Roles page, click "Invite User" or "Edit Access", and select their designated warehouse location from your company\'s active warehouses dropdown.' },
    { q: 'How do I generate a database backup?', a: 'Go to Settings -> Backup & Restore tab and click "Generate & Download Backup" to receive an immediate full SQL dump of your database records.' }
];

const filteredFaqs = computed(() => {
  if (!faqQuery.value) return faqs;
  const q = faqQuery.value.toLowerCase();
  return faqs.filter(f => f.q.toLowerCase().includes(q) || f.a.toLowerCase().includes(q));
});

// Ticket Details & Thread Replies Modal
const showDetailsModal = ref(false);
const selectedTicket = ref(null);
const replyMessage = ref('');
const updateStatusValue = ref('open');
const isSubmittingReply = ref(false);

const toggleImpersonation = (e) => {
    router.put('/support/impersonation', {
        allow: e.target.checked
    }, { preserveScroll: true });
};

const openTicketDetails = (ticket) => {
  selectedTicket.value = ticket;
  updateStatusValue.value = ticket.status || 'open';
  replyMessage.value = '';
  showDetailsModal.value = true;
};

const submitReply = () => {
  if (!replyMessage.value || !selectedTicket.value) return;

  isSubmittingReply.value = true;
  router.post(`/support/${selectedTicket.value.id}/reply`, {
    message: replyMessage.value
  }, {
    onSuccess: () => {
      replyMessage.value = '';
      if (updateStatusValue.value !== selectedTicket.value.status) {
        router.put(`/support/${selectedTicket.value.id}/status`, {
          status: updateStatusValue.value
        });
      }
      showDetailsModal.value = false;
    },
    onFinish: () => {
      isSubmittingReply.value = false;
    }
  });
};
</script>
