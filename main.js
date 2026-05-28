(function () {
  'use strict';

  const notificationBtn = document.querySelector('.notification-button');
  const searchInput = document.querySelector('.search-box input');
  const ticketBody = document.getElementById('ticket-body');
  const ticketTotal = document.getElementById('ticket-total');
  const ticketCount = document.getElementById('ticket-count');
  const cobrarBtn = document.getElementById('cobrar-btn');
  const cancelarBtn = document.getElementById('cancelar-btn');
  const productCards = document.querySelectorAll('.product-card');

  let ticketItems = [];

  function showToast(message, type) {
    const existing = document.querySelector('.toast-notification');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText =
      'position:fixed;bottom:24px;right:24px;padding:14px 22px;border-radius:14px;color:#fff;font-weight:700;font-size:14px;z-index:9999;box-shadow:0 12px 30px rgba(0,0,0,0.18);max-width:360px;';
    toast.style.background =
      type === 'success' ? '#22c55e' : type === 'error' ? '#ef4444' : '#3b82f6';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(function () {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.3s ease';
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

  function actualizarTicket() {
    if (!ticketBody) return;
    ticketBody.innerHTML = '';

    if (ticketItems.length === 0) {
      ticketBody.innerHTML =
        '<tr><td colspan="3" class="empty-state" style="text-align:center;padding:24px;color:#94a3b8;">Agrega productos al ticket</td></tr>';
      if (ticketTotal) ticketTotal.textContent = '$0.00';
      if (ticketCount) ticketCount.textContent = '0';
      return;
    }

    let total = 0;
    ticketItems.forEach(function (item, index) {
      const subtotal = item.precio * item.cantidad;
      total += subtotal;
      const row = document.createElement('tr');
      row.innerHTML =
        '<td style="padding:6px 0;">' +
        item.cantidad +
        ' x ' +
        item.nombre +
        '</td><td style="text-align:right;padding:6px 0;">$' +
        subtotal.toFixed(2) +
        '</td><td style="text-align:right;padding:6px 0;width:30px;"><button class="btn-remove-item" data-index="' +
        index +
        '" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:16px;">×</button></td>';
      ticketBody.appendChild(row);
    });

    if (ticketTotal) ticketTotal.textContent = '$' + total.toFixed(2);
    if (ticketCount) ticketCount.textContent = ticketItems.length;

    document.querySelectorAll('.btn-remove-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        ticketItems.splice(parseInt(this.getAttribute('data-index')), 1);
        actualizarTicket();
      });
    });
  }

  productCards.forEach(function (card) {
    const btn = card.querySelector('.btn-success');
    const nombreEl = card.querySelector('h4');
    const precioEl = card.querySelector('p');
    if (!btn || !nombreEl || !precioEl) return;

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const nombre = nombreEl.textContent;
      const precio = parseFloat(precioEl.textContent.replace('$', '')) || 0;
      const found = ticketItems.filter(function (i) { return i.nombre === nombre; });
      if (found.length > 0) {
        found[0].cantidad += 1;
      } else {
        ticketItems.push({ nombre: nombre, precio: precio, cantidad: 1 });
      }
      actualizarTicket();
      showToast(nombre + ' agregado al ticket', 'success');
    });
  });

  if (cobrarBtn) {
    cobrarBtn.addEventListener('click', function () {
      if (ticketItems.length === 0) {
        showToast('El ticket está vacío. Agrega productos primero.', 'error');
        return;
      }
      showToast('Venta cobrada por ' + (ticketTotal ? ticketTotal.textContent : '$0.00'), 'success');
      ticketItems = [];
      actualizarTicket();
    });
  }

  if (cancelarBtn) {
    cancelarBtn.addEventListener('click', function () {
      if (ticketItems.length === 0) {
        showToast('El ticket ya está vacío.', 'info');
        return;
      }
      ticketItems = [];
      actualizarTicket();
      showToast('Ticket cancelado.', 'info');
    });
  }

  document.querySelectorAll('form').forEach(function (form) {
    if (form.closest('.login-panel') || (form.action && form.action.indexOf('login.php') > -1)) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      showToast('Datos guardados correctamente', 'success');
    });
  });

  document.querySelectorAll('.btn-danger').forEach(function (btn) {
    if (btn.closest('.pos-ticket-sidebar') || btn.textContent.indexOf('Dar de Baja') === -1) return;
    btn.addEventListener('click', function () {
      var row = this.closest('tr');
      if (row) { row.style.opacity = '0.4'; }
      showToast('Registro desactivado correctamente', 'info');
    });
  });

  document.querySelectorAll('.btn-success').forEach(function (btn) {
    if (btn.textContent.indexOf('Reactivar') > -1) {
      btn.addEventListener('click', function () {
        var row = this.closest('tr');
        if (row) { row.style.opacity = '1'; }
        showToast('Registro reactivado correctamente', 'success');
      });
    }
    if (btn.textContent.indexOf('Registrar Entrada') > -1 || btn.textContent.indexOf('Registrar Salida') > -1) {
      btn.addEventListener('click', function () {
        showToast('Movimiento registrado correctamente', 'success');
      });
    }
  });

})();
