/**
 * Nexus E-Commerce Client API & Helper Library
 */

const API = {
  baseUrl: '/api',

  // Session ID for guest cart
  getSessionId() {
    let sid = localStorage.getItem('nexus_session_id');
    if (!sid) {
      sid = 'guest_' + Math.random().toString(36).substring(2, 12) + '_' + Date.now();
      localStorage.setItem('nexus_session_id', sid);
    }
    return sid;
  },

  // Token management
  getToken() {
    return localStorage.getItem('nexus_token') || null;
  },

  setToken(token) {
    if (token) {
      localStorage.setItem('nexus_token', token);
    } else {
      localStorage.removeItem('nexus_token');
    }
  },

  getUser() {
    try {
      const u = localStorage.getItem('nexus_user');
      return u ? JSON.parse(u) : null;
    } catch (e) {
      return null;
    }
  },

  setUser(user) {
    if (user) {
      localStorage.setItem('nexus_user', JSON.stringify(user));
    } else {
      localStorage.removeItem('nexus_user');
    }
  },

  isLoggedIn() {
    return !!this.getToken();
  },

  isAdmin() {
    const u = this.getUser();
    return u && u.role === 'admin';
  },

  logout() {
    this.setToken(null);
    this.setUser(null);
    window.location.href = '/login.html';
  },

  // Generic API request wrapper
  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}${endpoint}`;
    const headers = {
      'x-session-id': this.getSessionId(),
      ...(options.headers || {})
    };

    // Auto-attach JWT if present
    const token = this.getToken();
    if (token && !headers['Authorization']) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    // Default JSON Content-Type if body is not FormData
    if (options.body && !(options.body instanceof FormData) && !headers['Content-Type']) {
      headers['Content-Type'] = 'application/json';
    }

    try {
      const response = await fetch(url, {
        ...options,
        headers
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(data.message || `Request failed with status ${response.status}`);
      }

      return data;
    } catch (err) {
      console.error(`API Error [${endpoint}]:`, err);
      throw err;
    }
  },

  // Helper HTTP methods
  get(endpoint, params = {}) {
    const qs = new URLSearchParams(params).toString();
    return this.request(qs ? `${endpoint}?${qs}` : endpoint, { method: 'GET' });
  },

  post(endpoint, body) {
    return this.request(endpoint, {
      method: 'POST',
      body: body instanceof FormData ? body : JSON.stringify(body)
    });
  },

  put(endpoint, body) {
    return this.request(endpoint, {
      method: 'PUT',
      body: body instanceof FormData ? body : JSON.stringify(body)
    });
  },

  delete(endpoint) {
    return this.request(endpoint, { method: 'DELETE' });
  },

  // Toast Notifications
  showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon = type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-info-circle');
    toast.innerHTML = `
      <i class="fa-solid ${icon}"></i>
      <span>${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  },

  // Format currency in Indian Rupee
  formatPrice(amount) {
    const num = Number(amount) || 0;
    return new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
      maximumFractionDigits: 0
    }).format(num);
  },

  // Update navbar cart count
  async updateCartBadge() {
    try {
      const res = await this.get('/cart');
      if (res && res.summary) {
        const badges = document.querySelectorAll('.cart-count');
        badges.forEach(b => {
          b.textContent = res.summary.totalItems || 0;
          b.style.display = res.summary.totalItems > 0 ? 'flex' : 'none';
        });
      }
    } catch (e) {
      // quiet fail
    }
  }
};

window.API = API;
