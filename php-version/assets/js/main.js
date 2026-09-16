/**
 * Nexus Global Interactive Controller
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initial cart badge update
  if (window.API) {
    API.updateCartBadge();
  }

  // 2. Mobile Drawer controls
  const menuBtn = document.getElementById('mobileMenuBtn');
  const closeDrawerBtn = document.getElementById('closeDrawerBtn');
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');

  function openDrawer() {
    if (drawer) drawer.classList.add('open');
    if (backdrop) backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    if (drawer) drawer.classList.remove('open');
    if (backdrop) backdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (menuBtn) menuBtn.addEventListener('click', openDrawer);
  if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
  if (backdrop) backdrop.addEventListener('click', closeDrawer);

  // 3. Search inputs
  const searchInputs = document.querySelectorAll('.global-search-input');
  searchInputs.forEach(input => {
    input.addEventListener('keypress', (e) => {
      if (e.key === 'Enter' && input.value.trim()) {
        window.location.href = `/shop.html?search=${encodeURIComponent(input.value.trim())}`;
      }
    });
  });

  // 4. Update Header Authentication State
  updateHeaderAuth();

  // 5. Global Quick Add to Cart delegation
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.btn-quick-add');
    if (!btn) return;
    e.preventDefault();
    const productId = btn.dataset.productId;
    if (!productId) return;

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';

    try {
      const res = await API.post('/cart/add', { productId, quantity: 1 });
      API.showToast(res.message || 'Added to cart!', 'success');
      API.updateCartBadge();
    } catch (err) {
      API.showToast(err.message || 'Failed to add item', 'error');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  });
});

function updateHeaderAuth() {
  const user = API.getUser();
  const authContainers = document.querySelectorAll('.auth-nav-slot');

  authContainers.forEach(container => {
    if (user) {
      let adminLink = user.role === 'admin' ? `<li><a href="/admin/index.html"><i class="fa-solid fa-gauge-high"></i> Admin Panel</a></li>` : '';
      container.innerHTML = `
        <div class="user-dropdown-wrap" style="position: relative; display: inline-block;">
          <button class="btn btn-secondary btn-sm" id="userMenuBtn" style="border-radius: var(--radius-full); gap: 0.4rem;">
            <i class="fa-solid fa-circle-user"></i>
            <span>${user.name.split(' ')[0]}</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem;"></i>
          </button>
          <div class="user-menu-dropdown" id="userMenuDropdown" style="display: none; position: absolute; right: 0; top: 110%; background: #fff; min-width: 180px; box-shadow: var(--shadow-lg); border-radius: var(--radius-md); border: 1px solid var(--border); padding: 0.5rem; z-index: 200;">
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.25rem;">
              ${adminLink}
              <li><a href="/orders.html" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 6px; color: var(--text-main); font-size: 0.88rem; font-weight: 500;"><i class="fa-solid fa-box-archive"></i> My Orders</a></li>
              <li><button onclick="API.logout()" style="width: 100%; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 6px; color: var(--danger); background: none; border: none; font-size: 0.88rem; font-weight: 600; cursor: pointer; text-align: left;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button></li>
            </ul>
          </div>
        </div>
      `;

      const userBtn = container.querySelector('#userMenuBtn');
      const dropdown = container.querySelector('#userMenuDropdown');
      if (userBtn && dropdown) {
        userBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
        document.addEventListener('click', () => {
          dropdown.style.display = 'none';
        });
      }
    } else {
      container.innerHTML = `
        <a href="/login.html" class="btn btn-secondary btn-sm" style="border-radius: var(--radius-full);">
          <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
        </a>
      `;
    }
  });
}
