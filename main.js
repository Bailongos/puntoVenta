(function () {
  'use strict';

  const notificationBtn = document.querySelector('.notification-button');
  const searchInput = document.querySelector('.search-box input');

  function showToast(message, type) {
    const existing = document.querySelector('.toast-notification');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast-notification ' + (type || 'info');
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(function () {
      toast.style.opacity = '0';
      setTimeout(function () { toast.remove(); }, 300);
    }, 3000);
  }

  if (notificationBtn) {
    notificationBtn.addEventListener('click', function () {
      showToast('No tienes notificaciones pendientes.', 'info');
    });
  }

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const q = this.value.toLowerCase().trim();
      document.querySelectorAll('.data-table, .users-table, .activity-table table').forEach(function (table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(function (row) {
          if (row.classList.contains('empty-state')) return;
          row.style.display = row.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
        });
      });
    });
  }

  document.querySelectorAll('form').forEach(function (form) {
    if (form.closest('.login-panel')) return;
    if (form.action && form.action.indexOf('login.php') > -1) return;
    if (form.getAttribute('method')) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      showToast('Datos guardados correctamente', 'success');
    });
  });

  document.querySelectorAll('.btn-danger').forEach(function (btn) {
    if (btn.closest('.pos-ticket-sidebar') || btn.textContent.indexOf('Dar de Baja') === -1) return;
    if (btn.tagName === 'A') return;
    btn.addEventListener('click', function () {
      var row = this.closest('tr');
      if (row) { row.style.opacity = '0.4'; }
      showToast('Registro desactivado correctamente', 'info');
    });
  });

  document.querySelectorAll('form .btn').forEach(function (btn) {
    if (btn.closest('.pos-ticket-sidebar')) return;
    if (btn.tagName === 'A') return;
    if (btn.getAttribute('type') === 'submit') return;
    btn.addEventListener('click', function () {
      const row = this.closest('tr');
      if (row) row.style.opacity = this.textContent.indexOf('Reactivar') > -1 ? '1' : '0.4';
      showToast('Operación completada', 'success');
    });
  });

})();
