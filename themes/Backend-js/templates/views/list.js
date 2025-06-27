%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import { GenericListElement } from '../../components'
import { html } from 'lit'

import %%(self.obName.title())%%EditElement from './edit.js'
import %%(self.obName.title())%%CreateElement from './create.js'


export default class %%(self.obName.title())%%ListElement extends GenericListElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super()
        this.objectName = "%%(self.obName.lower())%%"
    }

    /** Override if needed
    urlOfList(){
        var sortQuery = ""
        if(this.orderBy != undefined){
            sortQuery = `&sort_by=${this.orderBy}&order=${this.asc?'asc':'desc'}`
        }
        return `/api/v2/%%(self.obName.lower())%%s/?page=${this.currentPage}${sortQuery}`
    }*/

    getEditorHtml(){
        return html`<app-%%(self.obName.lower())%%-edit id="editor" 
                .metadata=${ this.metadata }
                .user="${ this.user }">
            </app-%%(self.obName.lower())%%-edit>`
    }

    getCreatorHtml(){
        return html`<app-%%(self.obName.lower())%%-create id="creator"
                .metadata=${ this.metadata }
                .user="${ this.user }">
            </app-%%(self.obName.lower())%%-create>`
    }


}

window.customElements.define('app-%%(self.obName.lower())%%-list', %%(self.obName.title())%%ListElement);