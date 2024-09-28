%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import { LitElement, html } from 'lit';
import { until } from 'lit/directives/until.js'
import { translate, get } from 'lit-translate'

import %%(self.obName.title())%%EditElement from './edit.js'

const fields = [%%allAttributeCode = ""
for field in self.fields:
    referenceData = ""
    if field.referencedObject:
        referenceData = """
        references: {
            object: "%(refObject)s",
            key: "%(refKey)s",
            label: "%(refLabel)s",
        } """ % {
            'refObject': field.referencedObject.obName.lower(),
            'refKey': field.referencedObject.keyFields[0].dbName,
            'refLabel': field.display
        }

    attributeCode = """
    {
        key: "%(dbName)s",
        type: "%(fieldType)s",%(referenceData)s
    }""" % {
        'dbName': field.dbName,
        'fieldType': field.sqlType.lower(),
        'objectObName': self.obName.lower(),
        'referenceData': referenceData
    }

    if allAttributeCode != "":
        allAttributeCode += ","
    allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
]

const LABEL_SUFFIX = "_label"

export default class %%(self.obName.title())%%ListElement extends LitElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super()

        this.foreignFields = fields.filter(f => f.references != undefined)
        
        this.columns = fields.map((f) => {
            let column = Object.assign({}, f) // Make a copy of 'f'
            column.label = translate("object.building.field."+f.key)
            if( f.references != undefined){
                column.key += LABEL_SUFFIX // Update the key on 'column', not on 'f'
            }
            return column
        })
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
            call('/api/v1/buildings/', 'GET').then((responseOk, responseFailure) => {
                //console.log(responseOk)
                if(responseOk && responseOk.data){
                    this.loadAllForeignData(responseOk.data).then(() => {
                        resolve(responseOk.data)
                    })
                }else{
                    reject()
                }
            })
        })
    }

    loadAllForeignData(data){
        return new Promise((resolve, reject) => {
            let promises = [] 
            for(let foreignField of this.foreignFields){
                for(let index in data){
                    let rawValue = data[index][foreignField.key]
                    promises.push(this.loadForeignData(index, foreignField, rawValue))
                }
            }
            Promise.all(promises).then((result) => {
                for(let res of result){
                    data[res.index][res.field.key+LABEL_SUFFIX] = res.label
                }
                resolve()
            })
        })
    }

    loadForeignData(index, field, value){
        return new Promise((resolve, reject) => {
            call('/api/v1/'+field.references.object+'s/'+value, 'GET').then((responseOk, responseFailure) => {
                if(responseOk){
                    let result = {field, index, label: responseOk[field.references.label]}
                    resolve(result)
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