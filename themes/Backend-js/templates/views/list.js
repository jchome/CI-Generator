%[kind : views]
%[file : list.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import GenericListElement from '../list-generic.js'

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
        return '/api/v2/%%(self.obName.lower())%%s/?page=' + this.currentPage
    }*/
    
    /**
     * Convert data to another format
     * Override if needed
     * 
     * @param {Array} data 
     * @returns Array The data converted
     */
    convertData(data){
        // Default: don't convert data
        return super.convertData(data) 
        /*return data.map((item) => {
            if(item.photo){
                item.photo = unsafeHTML(`<img src="${this.conf.server.host}/uploads/${item.photo}" class="user-photo">`)
            }
            return item
        })*/
    }


}

window.customElements.define('app-%%(self.obName.lower())%%-list', %%(self.obName.title())%%ListElement);