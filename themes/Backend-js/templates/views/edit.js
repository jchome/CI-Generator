%[kind : views]
%[file : edit.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericEditElement from '../edit-generic.js';
import { html } from 'lit';


export default class %%(self.obName.title())%%EditElement extends GenericEditElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super()
        this.objectName = "%%(self.obName.lower())%%"
    }

    /** Override if needed
    urlOfSave(id){
        return `/api/v2/%%(self.obName.lower())%%s/${id}`
    }*/


}

window.customElements.define('app-%%(self.obName.lower())%%-edit', %%(self.obName.title())%%EditElement);