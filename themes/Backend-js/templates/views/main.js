%[kind : views]
%[file : main-%%(self.obName.lower())%%.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import { html } from 'lit'

import %%(self.obName.title())%%ListElement from './list.js'
import { GenericMainElement } from '../../components'


export default class Main%%(self.obName.title())%%Element extends GenericMainElement {

    renderMain() {
        return html`
            <app-%%(self.obName.lower())%%-list
                .user="${this.user}">
            </app-%%(self.obName.lower())%%-list>
        `
    }

}

customElements.define('app-main-%%(self.obName.lower())%%', Main%%(self.obName.title())%%Element);