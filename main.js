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

  function actualizarTicket() {
    if (!ticketBody) return;
    ticketBody.innerHTML = '';

    if (ticketItems.length === 0) {
      ticketBody.innerHTML =
        '<tr><td colspan="3" class="empty-state">Agrega productos al ticket</td></tr>';
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
        '<td>' +
        item.cantidad +
        ' x ' +
        item.nombre +
        '</td><td class="text-right">$' +
        subtotal.toFixed(2) +
        '</td><td class="td-actions"><button class="btn-remove-item" data-index="' +
        index +
        '">×</button></td>';
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

  function agregarAlTicket(nombre, precio) {
    const found = ticketItems.filter(function (i) { return i.nombre === nombre; });
    if (found.length > 0) {
      found[0].cantidad += 1;
    } else {
      ticketItems.push({ nombre: nombre, precio: precio, cantidad: 1 });
    }
    actualizarTicket();
    showToast(nombre + ' agregado al ticket', 'success');
  }

  productCards.forEach(function (card) {
    const btn = card.querySelector('.btn-success');
    const nombreEl = card.querySelector('h4');
    const precioEl = card.querySelector('p');
    if (!btn || !nombreEl || !precioEl) return;

    const nombre = nombreEl.textContent;
    const precio = parseFloat(precioEl.textContent.replace('$', '')) || 0;

    card.addEventListener('click', function () {
      agregarAlTicket(nombre, precio);
    });

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
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
    if (form.closest('.login-panel')) return;
    if (form.action && form.action.indexOf('login.php') > -1) return;
    if (form.getAttribute('method') && form.getAttribute('method').toUpperCase() === 'POST') return;
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

  document.querySelectorAll('.btn-success').forEach(function (btn) {
    if (btn.textContent.indexOf('Reactivar') > -1) {
      if (btn.tagName === 'A') return;
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
