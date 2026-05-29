            </main>

        </section>

    </div>

<div class="modal-overlay" id="ticket-modal">
    <div class="modal-panel">
        <div class="modal-heading">
            <h2 id="tm-folio">Ticket</h2>
            <button class="modal-close" id="tm-cerrar">&times;</button>
        </div>
        <div id="tm-body">
            <div class="flex-row" style="margin-bottom:12px;">
                <span><strong>Fecha:</strong> <span id="tm-fecha">-</span></span>
                <span><strong>Atendi&oacute;:</strong> <span id="tm-usuario">-</span></span>
                <span><strong>Total:</strong> <strong class="text-primary" id="tm-total">$0.00</strong></span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>C&oacute;digo</th>
                        <th>Descripci&oacute;n</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tm-items"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right fw-bold">Total:</td>
                        <td class="fw-bold text-primary" id="tm-total-foot">$0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo $root_path; ?>main.js"></script>
<script>
(function() {
    var tmModal = document.getElementById('ticket-modal');
    var tmCerrar = document.getElementById('tm-cerrar');

    if (tmCerrar) {
        tmCerrar.addEventListener('click', function() { tmModal.classList.remove('abierto'); });
    }
    if (tmModal) {
        tmModal.addEventListener('click', function(e) { if (e.target === tmModal) tmModal.classList.remove('abierto'); });
    }

    window.verTicket = function(id) {
        fetch('<?php echo $root_path; ?>ajax/ver_ticket.php?id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) { alert(data.error); return; }
                document.getElementById('tm-folio').textContent = 'Ticket: ' + data.folio;
                document.getElementById('tm-fecha').textContent = data.fecha;
                document.getElementById('tm-usuario').textContent = data.usuario;
                document.getElementById('tm-total').textContent = '$' + data.total;
                document.getElementById('tm-total-foot').textContent = '$' + data.total;
                var tbody = document.getElementById('tm-items');
                tbody.innerHTML = '';
                data.items.forEach(function(item) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = '<td>' + item.codigo_barras + '</td><td>' + item.descripcion +
                        '</td><td>' + item.cantidad + '</td><td>$' + parseFloat(item.precio_unitario).toFixed(2) +
                        '</td><td class="fw-bold">$' + parseFloat(item.subtotal).toFixed(2) + '</td>';
                    tbody.appendChild(tr);
                });
                tmModal.classList.add('abierto');
            })
            .catch(function(err) { alert('Error: ' + err.message); });
    };

    document.querySelectorAll('.ver-ticket').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id = parseInt(this.getAttribute('data-id'));
            if (id) verTicket(id);
        });
    });
})();
</script>
</body>
</html>
