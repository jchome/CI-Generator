%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericListElement from '../list-generic.js'

import %%(self.obName.title())%%EditElement from './edit.js'


export default class %%(self.obName.title())%%ListElement extends GenericListElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super('%%(self.obName.lower())%%')
    }

    /* Override if needed
    urlOfList(){
        var sortQuery = ""
        if(this.orderBy != undefined){
            sortQuery = `&sort_by=${this.orderBy}&order=${this.asc?'asc':'desc'}`
        }
        return `/api/v2/%%(self.obName.lower())%%s/?page=${this.currentPage}${sortQuery}`
    }*/


}

window.customElements.define('app-%%(self.obName.lower())%%-list', %%(self.obName.title())%%ListElement);