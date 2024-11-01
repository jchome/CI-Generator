%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericListElement from '../list-generic.js'
import { unsafeHTML } from 'lit/directives/unsafe-html.js';

import %%(self.obName.title())%%EditElement from './edit.js'


export default class %%(self.obName.title())%%ListElement extends GenericListElement {
    static properties = { }
    static get styles() { }

    constructor() {
        super('%%(self.obName.lower())%%',
            [%%allAttributeCode = ""
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
                    type: "%(fieldType)s",
                    show: true,%(referenceData)s
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
        )
    }

    /* Override if needed
    urlOfList(){
        var sortQuery = ""
        if(this.orderBy != undefined){
            sortQuery = `&sort_by=${this.orderBy}&order=${this.asc?'asc':'desc'}`
        }
        return '/api/v2/%%(self.obName.lower())%%s/?page=${this.currentPage}${sortQuery}`
    }*/
    
    /**
     * Convert data to another format
     * 
     * @param {Array} data 
     * @returns Array The data converted
     */
    convertData(data){
        return data.map((item) => {
%%allAttributeCode = ""
for field in self.fields:
    attributeCode = ""
    if field.sqlType.upper()[0:4] == "FILE":
        attributeCode = """
            if(item.%(dbName)s){
                item.%(dbName)s = unsafeHTML(`<img src="${this.conf.server.host}/uploads/${item.%(dbName)s}" class="img-fluid">`)
            }
        """ % { 'dbName' : field.dbName }
    elif field.sqlType.upper()[0:4] == "DATE":
        attributeCode = """
            if(item.%(dbName)s){
                item.%(dbName)s = item.%(dbName)s.split('-').reverse().join('/')
            }
        """  % { 'dbName' : field.dbName }

    allAttributeCode += attributeCode
RETURN = allAttributeCode
%%
            return item
        })
    }


}

window.customElements.define('app-%%(self.obName.lower())%%-list', %%(self.obName.title())%%ListElement);