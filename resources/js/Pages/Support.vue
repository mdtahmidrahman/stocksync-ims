<template>
  <div>
    <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white mb-6">Support & Help Center</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left Column: Tickets & Contact -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Contact Form Card -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Create Support Ticket</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Need help with the system? Reach out to the admin team.</p>
          
          <form class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Issue Category</label>
              <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
                <option>System Bug</option>
                <option>Inventory Discrepancy</option>
                <option>Billing Issue</option>
                <option>Other</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
              <input type="text" placeholder="Brief summary of the issue..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
              <textarea rows="4" placeholder="Detailed explanation..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm"></textarea>
            </div>
            <button type="button" class="w-full bg-primary-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm">
              Submit Ticket
            </button>
          </form>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">My Recent Tickets</h2>
          <div class="space-y-4">
            <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-3">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-medium text-gray-900 dark:text-white">Barcode Scanner not syncing</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">Open</span>
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">Ticket #8492 • Submitted 2 hrs ago</div>
            </div>
            <div class="border border-gray-100 dark:border-gray-800 rounded-lg p-3 opacity-60">
              <div class="flex justify-between items-start mb-1">
                <span class="text-sm font-medium text-gray-900 dark:text-white">Stock report export failing</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">Resolved</span>
              </div>
              <div class="text-xs text-gray-500 dark:text-gray-400">Ticket #8450 • Submitted 3 days ago</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: FAQs -->
      <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quick answers to common system operations.</p>
          </div>
          
          <div class="divide-y divide-gray-100 dark:divide-gray-800">
            <div class="p-6" v-for="(faq, i) in faqs" :key="i">
              <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">{{ faq.q }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ faq.a }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
const faqs = [
    { q: 'How do I transfer stock between warehouses?', a: 'Navigate to the Inventory page, click "Sync Stock", and select "Transfer" as the operation type. You will need to select the Source and Destination warehouse before confirming the quantity.' },
    { q: 'Can I export reports to PDF?', a: 'Currently, the system exports to CSV format for maximum compatibility with spreadsheet software like Excel. PDF exporting is planned for the Q3 system update.' },
    { q: 'What happens when an item reaches its Rec. Level?', a: 'The system will automatically flag the item in the "Quantity Alert" section of the Reports page. If email notifications are enabled in Settings, the Store Manager will receive a daily digest of low stock items.' },
    { q: 'How do I reset a staff member\'s password?', a: 'As an Admin, navigate to the Roles page, click "Edit Access" next to the staff member\'s name, and click the "Send Password Reset Link" button at the bottom of their profile.' },
    { q: 'Why is a Purchase Order showing as "Pending" after payment?', a: 'Purchase Orders track both payment status and receipt status independently. An order will remain "Pending" until the physical goods have been marked as "Received" in the system, regardless of whether it has been paid.' }
];
</script>
