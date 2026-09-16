/**
 * Nexus Admin Panel Controller & Security Guard
 */

// Immediate Auth Guard: Protect admin pages from unauthorized public access
(function checkAdminAuth() {
  const token = localStorage.getItem('nexus_token');
  let user = null;
  try {
    user = JSON.parse(localStorage.getItem('nexus_user'));
  } catch (e) {}

  if (!token || !user || user.role !== 'admin') {
    window.location.href = '/login.html?redirect=' + encodeURIComponent(window.location.pathname);
  }
})();

const AdminApp = {
  // Upload image to server
  async uploadImage(fileInput) {
    if (!fileInput.files || fileInput.files.length === 0) return null;
    const formData = new FormData();
    formData.append('image', fileInput.files[0]);

    const res = await API.post('/admin/upload', formData);
    return res.url;
  },

  // Modal helpers
  openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('active');
  },

  closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('active');
  },

  // Render Admin Topbar and Sidebar Profile
  initLayout(activeNav) {
    const user = API.getUser();
    const adminNameEl = document.getElementById('adminUserName');
    if (adminNameEl && user) {
      adminNameEl.textContent = user.name;
    }

    // Highlight active nav item
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    navItems.forEach(item => {
      if (item.dataset.nav === activeNav) {
        item.classList.add('active');
      } else {
        item.classList.remove('active');
      }
    });

    // Mobile sidebar toggle
    const toggleBtn = document.getElementById('adminSidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (toggleBtn && sidebar) {
      toggleBtn.onclick = () => {
        sidebar.classList.toggle('open');
      };
    }
  }
};

window.AdminApp = AdminApp;
