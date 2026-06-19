<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">User Roles & Team</h1>
      <button class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        Invite User
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input type="text" placeholder="Search team member..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <select class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
              <option>All Roles</option>
              <option>Admin</option>
              <option>Manager</option>
              <option>Staff</option>
            </select>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Employee</th>
              <th class="p-4 font-semibold whitespace-nowrap">Role</th>
              <th class="p-4 font-semibold whitespace-nowrap">Location/Warehouse</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap">Last Login</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" v-for="(user, i) in users" :key="i">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold">
                    {{ user.initials }}
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ user.name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <span :class="roleClass(user.role)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ user.role }}
                </span>
              </td>
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400">{{ user.location }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                  Active
                </span>
              </td>
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400">{{ i === 0 ? 'Just now' : i + ' days ago' }}</td>
              <td class="p-4 text-right whitespace-nowrap">
                <button class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-sm font-medium transition-colors">
                  Edit Access
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
const users = [
    { name: 'Demo Admin', email: 'admin@democompany.com', initials: 'DA', role: 'Super Admin', location: 'All Locations' },
    { name: 'Sarah Jenkins', email: 'sarah@democompany.com', initials: 'SJ', role: 'Store Manager', location: 'West Coast Storage' },
    { name: 'Michael Scott', email: 'michael@democompany.com', initials: 'MS', role: 'Store Manager', location: 'Central Distribution' },
    { name: 'Dwight Schrute', email: 'dwight@democompany.com', initials: 'DS', role: 'Floor Staff', location: 'Central Distribution' },
    { name: 'Jim Halpert', email: 'jim@democompany.com', initials: 'JH', role: 'Floor Staff', location: 'Central Distribution' },
];

const roleClass = (role) => {
    if (role === 'Super Admin') return 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300';
    if (role === 'Store Manager') return 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300';
    return 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300';
};
</script>
