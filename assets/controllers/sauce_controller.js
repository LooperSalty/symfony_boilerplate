import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['sauce', 'selected'];

    connect() {
        console.log('Sauce controller connected');
    }

    selectSauce(event) {
        const sauceName = event.target.value;
        this.selectedTarget.textContent = `Vous avez sélectionné : ${sauceName}`;
    }
}
