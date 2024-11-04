%[kind : views]
%[file : edit.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericEditElement from '../edit-generic';


export default class %%(self.obName.title())%%EditElement extends GenericEditElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super("%%(self.obName.lower())%%")
    }


}

window.customElements.define('app-%%(self.obName.lower())%%-edit', %%(self.obName.title())%%EditElement);