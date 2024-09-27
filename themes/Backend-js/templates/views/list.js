%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import { LitElement, html } from 'lit';
import { until } from 'lit/directives/until.js'
import { translate, get } from 'lit-translate'

import %%(self.obName.title())%%EditElement from './edit.js'


export default class %%(self.obName.title())%%ListElement extends LitElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super()
        this.columns = [%%allAttributeCode = ""
for field in self.fields:
    attributeCode = """
            {
                key: "%(dbName)s",
                type: "%(fieldType)s",
                label: translate("object.%(objectObName)s.field.%(dbName)s")
            }""" % {
                'dbName': field.dbName,
                'fieldType': field.sqlType.lower(),
                'objectObName': self.obName.lower(),
            }
    if allAttributeCode != "":
        allAttributeCode += ","
    allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
        ]
        this.actions = [
            {
                code: 'edit', 
                cssClass: 'btn btn-sm btn-primary mx-2 action-edit'
            },
            {
                code: 'delete', 
                cssClass: 'btn btn-sm btn-danger mx-2 action-delete'
            }
        ]

        this.addEventListener('edit', this.onEdit)
        this.addEventListener('delete', this.onDelete)
    }

    /**
     * Don't use the shadow-root node. The "styles" property will not be used.
     * @returns 
     */
    createRenderRoot() {
        return this;
    }

	
    /**
     * Called by update() and should be implemented to return a renderable 
     *   result (such as a TemplateResult) used to render the component's DOM.
     *
     * Updates?    No. Property changes inside this method do not trigger an element update.
     * Call super? Not necessary.
     * 
     * @returns html
     */
    render() {
        return until(this.loadData().then((data, failure)=> {
            this.items = data;
            return html`
                <h1 class="m-2">${translate("object.%%(self.obName.lower())%%.title-list")}</h1>
                <app-table 
                    .items=${ this.items }
                    .columns=${ this.columns }
                    .actions=${ this.actions }
                ></app-table>
                <div class="d-flex flex-row justify-content-around">
                    <div>
                        pagination
                    </div>
                    <div>
                        <button class="btn btn-primary m-2"
                            @click=${ this.onAdd }>
                            ${ translate("action.add") }
                        </button>
                    </div>
                </div>

                <app-%%(self.obName.lower())%%-edit></app-%%(self.obName.lower())%%-edit>
                `
            }), html`
            <div class="centered-loader flex-column">
                <div class="mb-2">
                    ${ get("app-login.loading") }
                </div>
                <div class="loader spinner-border" role="status">
                    <span class="visually-hidden">
                    ${ get("app-login.loading") }</span>
                </div>
            </div>`)
    }

    loadData(){
        return new Promise((resolve, reject) => {
            call('/api/v1/%%(self.obName.lower())%%s/', 'GET').then((responseOk, responseFailure) => {
                //console.log(responseOk)
                if(responseOk && responseOk.data){
                    resolve(responseOk.data)
                }else{
                    reject()
                }
            })
        })
    }

    onEdit(event){
        const item = event.detail.item
        this.querySelector("app-%%(self.obName.lower())%%-edit").open(item)
    }

    onDelete(event){
        if(!confirm(get("message.delete-confirm"))){
            return
        }
        console.log(event)
    }

    onAdd(event){
        console.log(event)
    }
}

window.customElements.define('app-%%(self.obName.lower())%%-list', %%(self.obName.title())%%ListElement);