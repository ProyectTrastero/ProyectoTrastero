// -------------------------------
  // Modal
  // -------------------------------
  const perfilModal = document.getElementById('perfilModal')
  if (perfilModal) {
    perfilModal.addEventListener('show.bs.modal', event => {
      // Button that triggered the modal
      const button = event.relatedTarget;
      // Extract info from data-bs-* attributes
      const campo = button.getAttribute('data-bs-campo');
      const campoValue = button.getAttribute('data-bs-value');

      // Update the modal's content.
      const modalTitle = perfilModal.querySelector('.modal-title');
      const lblCampo = perfilModal.querySelector('#lblCampo');
      const lblNewCampo = perfilModal.querySelector('#lblNewCampo');
      const inputCampo = perfilModal.querySelector('#inputCampo');
      const inputNewCampo = perfilModal.querySelector('#inputNewCampo')

      modalTitle.textContent = `Editar ${campo}`;
      lblCampo.innerHTML = campo;
      lblNewCampo.innerHTML = "Nuevo " + campo;
      inputCampo.value = campoValue;
      inputNewCampo.value = "";
    })
  }
  