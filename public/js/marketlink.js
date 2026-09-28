'use strict';

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

if (typeof axios !== 'undefined') {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = CSRF_TOKEN;
}

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initQuantityControls();
  initFavoriteButtons();
  initAiAssistant();
  initPasswordToggles();
  initCartForms();
  initAlertDismissal();
  initNavbarScroll();
});

function initSidebar() {
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const sidebar = document.querySelector('.portal-sidebar');

  if (!toggleBtn || !sidebar) return;

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('sidebar-open');
  });

  document.addEventListener('click', (e) => {
    if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove('sidebar-open');
    }
  });
}

function initQuantityControls() {
  document.querySelectorAll('.qty-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
      const action = this.dataset.action;
      const input = this.closest('.qty-control')?.querySelector('.qty-input');
      if (!input) return;

      const val = parseInt(input.value, 10) || 1;
      const max = parseInt(input.max, 10) || 999;

      if (action === 'increase' && val < max) {
        input.value = val + 1;
      } else if (action === 'decrease' && val > 1) {
        input.value = val - 1;
      }

      input.dispatchEvent(new Event('change'));
    });
  });
}

function initFavoriteButtons() {
  document.querySelectorAll('.favorite-btn').forEach((btn) => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const { productId, farmerId } = this.dataset;
      const icon = this.querySelector('i');
      const payload = {};

      if (productId) payload.product_id = productId;
      if (farmerId) payload.farmer_id = farmerId;

      fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
        .then((r) => {
          if (r.status === 401) {
            window.location.href = '/login';
            return null;
          }
          return r.json();
        })
        .then((data) => {
          if (!data) return;

          const isFavorited = data.status === 'added' || data.favorited;
          this.classList.toggle('active-fav', isFavorited);

          if (icon) {
            icon.className = isFavorited ? 'bi bi-heart-fill text-danger' : 'bi bi-heart';
          }

          showToast(data.message || (isFavorited ? 'Added to favorites' : 'Removed from favorites'), isFavorited ? 'success' : 'info');
        })
        .catch((err) => {
          console.error('Favorite toggle failed:', err);
        });
    });
  });
}

function initAiAssistant() {
  const aiForm = document.getElementById('ai-assistant-form');
  if (!aiForm) return;

  aiForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const input = document.getElementById('ai-user-prompt');
    const prompt = input?.value.trim();
    if (!prompt) return;

    sendAiPrompt(prompt);
    input.value = '';
  });
}

function sendAiPrompt(prompt) {
  const messages = document.getElementById('ai-chat-messages');
  if (!messages) return;

  const userBubble = document.createElement('div');
  userBubble.className = 'chat-bubble user';
  userBubble.textContent = prompt;
  messages.appendChild(userBubble);

  const loadingBubble = document.createElement('div');
  loadingBubble.className = 'chat-bubble bot';
  loadingBubble.innerHTML = '<span class="spinner-border spinner-border-sm text-success me-2"></span>Searching...';
  messages.appendChild(loadingBubble);
  messages.scrollTop = messages.scrollHeight;

  fetch('/ai/ask', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': CSRF_TOKEN,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ prompt })
  })
    .then((r) => r.json())
    .then((data) => {
      loadingBubble.innerHTML = data.response || 'Sorry, I could not find an answer right now.';
      messages.scrollTop = messages.scrollHeight;
    })
    .catch(() => {
      loadingBubble.innerHTML = 'Connection error. Please try again.';
    });
}

function initAlertDismissal() {
  setTimeout(() => {
    document.querySelectorAll('.alert.alert-success, .alert.alert-info').forEach((el) => {
      const bsAlert = bootstrap?.Alert?.getOrCreateInstance(el);
      if (bsAlert) bsAlert.close();
    });
  }, 5000);
}

function initPasswordToggles() {
  document.querySelectorAll('[id^="togglePw"]').forEach((btn) => {
    btn.addEventListener('click', function () {
      const inputId = this.id.replace('togglePw', 'password_');
      const input = document.getElementById(inputId) ||
                    this.closest('.input-group')?.querySelector('input[type="password"], input[type="text"]');
      if (!input) return;

      const icon = this.querySelector('i');
      const isPassword = input.type === 'password';

      input.type = isPassword ? 'text' : 'password';
      if (icon) {
        icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
      }
    });
  });
}

function initCartForms() {
  document.querySelectorAll('.add-to-cart-form').forEach((form) => {
    form.addEventListener('submit', function () {
      const btn = this.querySelector('[type="submit"]');
      if (btn) {
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
        setTimeout(() => {
          btn.disabled = false;
          btn.innerHTML = originalText;
        }, 2500);
      }
    });
  });
}

function initNavbarScroll() {
  const navbar = document.getElementById('main-navbar');
  if (!navbar) return;

  const handleScroll = () => {
    navbar.classList.toggle('nav-scrolled', window.scrollY > 40);
  };

  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();
}

function showToast(message, type = 'success') {
  const colors = {
    success: '#2E7D5B',
    error: '#dc2626',
    warning: '#d97706',
    info: '#3b82f6'
  };

  if (typeof Swal !== 'undefined') {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: type,
      title: message,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      customClass: { popup: 'rounded-4 shadow-lg' }
    });
  } else {
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;background:${colors[type] || colors.info};color:#fff;padding:1rem 1.5rem;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.2);font-weight:600;font-size:0.9rem;max-width:320px;`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3200);
  }
}

function confirmDelete(formId, message) {
  const text = message || 'This action cannot be undone.';

  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: 'Are you sure?',
      text: text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, delete it!',
      customClass: { popup: 'rounded-4' }
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(formId)?.submit();
      }
    });
  } else if (confirm(text)) {
    document.getElementById(formId)?.submit();
  }
}
