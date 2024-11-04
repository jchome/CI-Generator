%[kind : views]
%[file : create.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericCreateElement from '../create-generic.js';
import { html } from 'lit';


export default class %%(self.obName.title())%%CreateElement extends GenericCreateElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super()
        this.objectName = "%%(self.obName.lower())%%"
    }

    /** Override if needed
    urlOfSave(){
        return `/api/v1/%%(self.obName.lower())%%s/`
    }*/


}

window.customElements.define('app-%%(self.obName.lower())%%-create', %%(self.obName.title())%%CreateElement);