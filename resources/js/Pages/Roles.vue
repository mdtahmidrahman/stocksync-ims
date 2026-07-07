<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">User Roles & Team</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
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
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                  {{ filterRole }}
                  <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" v-for="role in ['All Roles', 'Company Admin', 'Manager', 'Staff']" :key="role" @click.prevent="filterRole = role; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="filterRole === role ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ role }}</a>
              </template>
            </Dropdown>
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
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="(user, i) in users" :key="i">
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
                  {{ user.roleName }}
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
                <button @click="openEditModal(user)" class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-sm font-medium transition-colors">
                  Edit Access
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Invite User Modal -->
    <Modal :show="showAddModal" :scrollable="false" @close="showAddModal = false" @save="handleInvite">
      <template #title>Invite Team Member</template>
      <template #body>
        <div class="space-y-4">
          <div v-if="error" class="p-3 text-xs font-medium rounded-xl bg-red-50 text-red-600 border border-red-100">
             {{ error }}
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
            <input type="text" v-model="newName" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="John Doe" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input type="email" v-model="newEmail" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="employee@company.com" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Role</label>
              <Dropdown align="left" width="full" fullWidth>
                <template #trigger>
                  <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                    <span :class="!newRole ? 'text-gray-500' : ''">{{ newRole || 'Select Role...' }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                  </button>
                </template>
                <template #content="{ close }">
                  <a href="#" v-for="role in ['Manager', 'Staff']" :key="role" @click.prevent="newRole = role; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="newRole === role ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ role }}</a>
                </template>
              </Dropdown>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Location</label>
              <Dropdown align="left" width="full" fullWidth>
                <template #trigger>
                  <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                    {{ newLocation }}
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                  </button>
                </template>
                <template #content="{ close }">
                  <a href="#" v-for="loc in ['All Locations', 'Central Distribution Hub', 'West Coast Storage', 'East Coast Fulfillment']" :key="loc" @click.prevent="newLocation = loc; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="newLocation === loc ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ loc }}</a>
                </template>
              </Dropdown>
            </div>
          </div>
          <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Permissions Preview</h4>
            <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1 list-disc list-inside">
              <li>Select a role above to view granted permissions.</li>
              <li>Users will receive an email invitation to set their password.</li>
            </ul>
          </div>
        </div>
      </template>
    </Modal>

    <!-- Edit User Modal -->
    <Modal :show="showEditModal" :scrollable="false" @close="showEditModal = false" @save="handleEdit">
      <template #title>Edit Team Member Access</template>
      <template #body>
        <div class="space-y-4">
          <div v-if="editError" class="p-3 text-xs font-medium rounded-xl bg-red-50 text-red-600 border border-red-100">
             {{ editError }}
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
            <input type="text" :value="editingUser?.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-500 sm:text-sm cursor-not-allowed" disabled />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input type="email" :value="editingUser?.email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-500 sm:text-sm cursor-not-allowed" disabled />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign Role</label>
            <Dropdown align="left" width="full" fullWidth>
              <template #trigger>
                <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                  <span :class="!editRole ? 'text-gray-500' : ''">{{ editRole || 'Select Role...' }}</span>
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" v-for="role in ['Manager', 'Staff']" :key="role" @click.prevent="editRole = role; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="editRole === role ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ role }}</a>
              </template>
            </Dropdown>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-between w-full items-center">
          <button @click="handleDelete(editingUser?.id)" class="text-sm font-semibold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">Revoke Access</button>
          <div class="flex gap-2">
            <button @click="showEditModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
            <button @click="handleEdit" :disabled="isEditing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save Changes</button>
          </div>
        </div>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Modal from '../Components/Modal.vue';

const props = defineProps({
    team: {
        type: Array,
        default: () => []
    }
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const filterRole = ref('All Roles');

// Add Form
const newRole = ref('');
const newEmail = ref('');
const newName = ref('');
const newLocation = ref('All Locations'); // Placeholder for now

// Edit Form
const editingUser = ref(null);
const editRole = ref('');
const editError = ref('');
const isEditing = ref(false);

const users = ref([]);
const isInviting = ref(false);
const error = ref('');

const formatTeam = (teamData) => {
    return (teamData || []).map(u => ({
        ...u,
        initials: u.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase(),
        location: 'HQ',
        roleName: u.role === 'admin' ? 'Company Admin' : (u.role === 'manager' ? 'Manager' : 'Staff')
    }));
};

users.value = formatTeam(props.team);

watch(() => props.team, (newTeam) => {
    users.value = formatTeam(newTeam);
}, { deep: true, immediate: true });

const fetchTeam = () => {
    router.reload({ only: ['team'] });
};

const handleInvite = async () => {
    error.value = '';
    
    if (!newName.value || !newEmail.value || !newRole.value) {
        error.value = 'Please fill out all fields.';
        return;
    }

    // Map UI role to backend role enum
    const backendRole = newRole.value === 'Manager' ? 'manager' : 'staff';

    isInviting.value = true;
    try {
        await axios.post('/api/team', {
            name: newName.value,
            email: newEmail.value,
            role: backendRole
        });
        showAddModal.value = false;
        fetchTeam(); // Refresh the list
        
        // Reset form
        newName.value = '';
        newEmail.value = '';
        newRole.value = '';
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to invite user.';
    } finally {
        isInviting.value = false;
    }
};

const openEditModal = (user) => {
    if (user.role === 'admin') {
        alert('You cannot edit the Company Admin role.');
        return;
    }
    
    editingUser.value = { ...user };
    editRole.value = user.role === 'manager' ? 'Manager' : 'Staff';
    editError.value = '';
    showEditModal.value = true;
};

const handleEdit = async () => {
    if (!editRole.value) {
        editError.value = 'Please select a role.';
        return;
    }

    const backendRole = editRole.value === 'Manager' ? 'manager' : 'staff';
    isEditing.value = true;
    editError.value = '';

    try {
        await axios.put(`/api/team/${editingUser.value.id}`, {
            role: backendRole
        });
        showEditModal.value = false;
        fetchTeam();
    } catch (err) {
        editError.value = err.response?.data?.message || 'Failed to update user.';
    } finally {
        isEditing.value = false;
    }
};

const handleDelete = async (userId) => {
    if (!confirm('Are you sure you want to completely revoke this user\'s access to the system?')) {
        return;
    }

    try {
        await axios.delete(`/api/team/${userId}`);
        showEditModal.value = false;
        fetchTeam();
    } catch (err) {
        editError.value = err.response?.data?.message || 'Failed to revoke access.';
    }
};

const roleClass = (role) => {
    if (role === 'admin') return 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300';
    if (role === 'manager') return 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300';
    return 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300';
};
</script>
