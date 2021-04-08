import { useDispatch } from 'stimulus-use'
import { Modal } from 'bootstrap';
import { Controller } from 'stimulus'

export default class extends Controller {
  static targets = ['modal', 'modalBody']
  static values = {formUrl: String}
  modal = null

  connect() {
    useDispatch(this);
  }

  async openModal() {
    this.modalBodyTarget.innerHTML = 'Loadding...'
    this.modal = new Modal(this.modalTarget)
    this.modal.show()
    const params = 'form=1'
    const response = await fetch(`${this.formUrlValue}?${params}`)
    this.modalBodyTarget.innerHTML = await response.text()
  }

  async submitForm(event) {
    event.preventDefault()
    const formElement = this.modalBodyTarget.getElementsByTagName('form')[0]
    try {
      const response = await fetch(`${this.formUrlValue}`, {
        method: 'POST',
        body: new FormData(formElement)
      })
      this.modal.hide();
      this.dispatch('success');
    } catch (e) {
      this.modalBodyTarget.innerHTML = e.responseText;
    }
  }

  modalHidden() {
    console.log('it was hidden')
  }
}
