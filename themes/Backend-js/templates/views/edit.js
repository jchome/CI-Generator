%[kind : views]
%[file : edit.js] 
%[path : app/assets/generated/%%(self.obName.lower())%%]
import { LitElement, html } from 'lit';
import { Toast, Modal } from 'bootstrap';
import { translate, get } from 'lit-translate'


export default class %%(self.obName.title())%%EditElement extends LitElement {
    static properties = { 
        objectId: {type: Number}
    }
    static get styles() { }

    constructor() {
        super()
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
        return html`
            <div class="modal fade" id="edit%%(self.obName.title())%%Modal" tabindex="-1" 
                 data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            ${translate("object.%%(self.obName.lower())%%.title-edit")}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" 
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
%%allAttributesCode = ""

for field in self.fields:
	attributeCode = ""
	if field.autoincrement:
		## ne pas presenter les champs auto-increment
		attributeCode = "<!-- AUTO_INCREMENT : DO NOT DISPLAY THIS ATTRIBUTE - " + attributeCode + " -->"
		continue
	
	attributeCode += """
	<div class="row mb-3"><!-- %(obName)s : %(desc)s -->
		<label for="%(dbName)s" class="col-2 col-form-label">""" % { 'dbName' : field.dbName, 'obName' : field.obName,'desc' : field.description }

	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		## The Required attribute is not valid for FLAG field
		attributeCode += "* "

	attributeCode += """${ translate("object.%(objectObName)s.field.%(dbName)s") }
		</label>
		""" % { 'dbName' : field.dbName, 'objectObName' : self.obName.lower() }

	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		<div class="col-3">"""
	else:
		attributeCode += """
		<div class="col-10">"""
	
	moreAttributes = ""
	if field.sqlType.upper()[0:4] != "FLAG" and not field.nullable:
		moreAttributes = "required "
			
	if field.referencedObject and field.access == "default" :
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" aria-describedby="%(dbName)sHelp" 
				class="form-control">""" % { 'dbName' : field.dbName }
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
		attributeCode += """
				<?php foreach ($%(referencedObject)sCollection as $%(referencedObject)sElt): ?>
				<option value="">xxx </option>
				<?php endforeach;?>
			</select>""" % { 'display' : field.display, 
				'keyReference' : field.referencedObject.keyFields[0].dbName, 
				'referencedObject' : field.referencedObject.obName.lower(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName}
				
	elif field.referencedObject and field.access == "ajax" :
		attributeCode += """
			<input type="text" name="%(dbName)s_text" id="%(dbName)s_text" 
				aria-describedby="%(dbName)sHelp" class="form-control" 
				value="<?= $%(dbName)s_text['%(display)s'] ?>" autocomplete="off" %(moreAttributes)s/>
			<input type="hidden" name="%(dbName)s" id="%(dbName)s" 
				value="<?= $%(structureObName)s['%(dbName)s'] ?>">""" % { 'dbName' : field.dbName,
				'referencedObject' : field.referencedObject.obName, 
				'structureObName' : self.obName.lower(),
				'display' : field.display,
				'moreAttributes' : moreAttributes
			 }
		
	elif field.sqlType.upper()[0:4] == "DATE":
		dateFormat = field.sqlType[5:-1]
		attributeCode += """
			<div class="input-group input-append date" data-date-format="%(dateFormat)s" id="datepicker_%(dbName)s">
				<input type="text" name="%(dbName)s" id="%(dbName)s" class="form-control" size="8" 
					aria-describedby="%(dbName)sHelp" maxlength="10" value="" %(moreAttributes)s> 
				<span class="add-on"></span>
				<span class="input-group-text" id="basic-addon2"><i class="bi bi-calendar-event"></i></span>
			</div>""" % { 'dbName' : field.dbName, 
			'dateFormat' : dateFormat,
			'moreAttributes' : moreAttributes
		}
		
	elif field.sqlType.upper()[0:8] == "PASSWORD":
		attributeCode += """
			<input type="password" id="%(dbName)s" name="%(dbName)s" 
					aria-describedby="%(dbName)sHelp" class="form-control" id="%(dbName)s" 
					value="" %(moreAttributes)s>""" % { 'dbName' : field.dbName, 
			'moreAttributes' : moreAttributes}
		
	elif field.sqlType.upper()[0:4] == "TEXT":
		attributeCode += """
			<input id="%(dbName)s" type="hidden" name="%(dbName)s" 
				value="" %(moreAttributes)s>
			<trix-editor input="%(dbName)s"></trix-editor>""" % { 
			'dbName' : field.dbName, 
			'moreAttributes' : moreAttributes,
			'structureObName' : self.obName.lower()
			}
		
	elif field.sqlType.upper()[0:4] == "FILE":
		attributeCode += """
			<?php if($%(structureObName)s['%(dbName)s'] != "") { ?>
			<div class="row">
				<img src="<?= base_url() ?>/uploads/<?= $%(structureObName)s['%(dbName)s'] ?>"class="col-4 img-fluid mb-4" style="width: 150px;">
			</div>
			<div class="row">
				<div class="col-2"><i> ${translate("action.current-file")} </i></div>
				<div class="col-2" id="%(dbName)s_currentFile">
					<a href="<?= base_url() ?>/uploads/" target="_new" class="btn btn-primary btn-sm">
						<i class="bi bi-file-earmark-fill"></i>${translate("action.download")}
					</a>
				</div>
				<div class="col-2" id="%(dbName)s_deleteButton">
					<a onclick='deleteFile_%(dbName)s()' class="btn btn-danger btn-sm">
						<i class="bi bi-trash"></i>${translate("action.delete")} 
					</a>
				</div>
			</div>
			<hr/>
			<?php } ?>
			<div class="row">
				<div class="col-2"><i> App.form.file.new </i></div>
				<div class="col-10">
					<input class="input-file" id="%(dbName)s_file" name="%(dbName)s_file" class="form-control" type="file">
					<input type="hidden" name="%(dbName)s" id="%(dbName)s" value="">
				</div>
			</div>""" % { 'dbName' : field.dbName, 
				'structureObName': self.obName.lower(),
				'moreAttributes' : moreAttributes
			}

	elif field.sqlType.upper()[0:4] == "FLAG":
		label = field.sqlType[5:-1].strip('"').strip("'")
		attributeCode += """
			<label class="checkbox">
				<input name="%(dbName)s" id="%(dbName)s" value="O" type="checkbox">
                %(label)s
			</label>""" % { 'dbName' : field.dbName, 
				'label': label.strip(), 
				'structureObName' : self.obName.lower() }
		
	elif field.sqlType.upper()[0:4] == "ENUM":
		attributeCode += """
			<select name="%(dbName)s" id="%(dbName)s" class="form-control" 
				aria-describedby="%(dbName)sHelp" %(moreAttributes)s>""" % { 
			'dbName' : field.dbName,
			'moreAttributes' : moreAttributes }
		
		if field.nullable:
			attributeCode += """
				<option value=""></option>"""
			
		enumTypes = field.sqlType[5:-1]
		for enum in enumTypes.split(','):
			valueAndText = enum.replace('"','').replace("'","").split(':')
			attributeCode += """
				<option value="" >%(text)s</option>""" % {'value': valueAndText[0].strip(), 
				'text': valueAndText[1].strip(), 
				'structureObName' : self.obName.lower(),
				'dbName' : field.dbName }
		attributeCode += """
			</select>"""

	else:
		# for string, int, ...
		attributeCode += """
			<input class="form-control" type="text" name="%(dbName)s" 
			aria-describedby="%(dbName)sHelp" id="%(dbName)s" value="" %(moreAttributes)s """ % { 
				'dbName' : field.dbName, 
				'moreAttributes' : moreAttributes
				}
		if field.getAttribute("check") and field.getAttribute("check") != "" :
			attributeCode += """onblur="checkField(this,%(regexp)s)" """ % {'regexp' : field.getAttribute("check")}
			attributeCode += """>""" % {'dbName' : field.dbName}
		else:
			attributeCode += ">"
			
	attributeCode += """
			<span id="%(dbName)sHelp" class="form-text">
				<?= lang('generated/%(objectObName)s.form.%(dbName)s.description')?>
			</span>
		</div>""" % {'dbName' : field.dbName, 'objectObName' : self.obName.title() }
	
		
	## Add the "today" button
	if field.sqlType.upper()[0:4] == "DATE":
		attributeCode += """
		<div class="col-3">
			<button class="btn btn-primary btn-sm" onclick="return today(%(dbName)s)">Aujoud'hui</button>
		</div>""" % {'dbName' : field.dbName}

	attributeCode += """
	</div>""" 
	

	# ajouter le nouvel attribut, avec indentation si ce n'est pas le premier
	if allAttributesCode != "":
		allAttributesCode += "\n\t" 
	allAttributesCode += attributeCode

RETURN =  allAttributesCode
%%

<!--
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
-->


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" 
                            data-bs-dismiss="modal">
                            ${ translate("action.cancel") }
                            </button>
                        <button type="button" class="btn btn-primary" @click=${this.onApply}>
                            ${ translate("action.apply") }
                        </button>
                    </div>
                    </div>
                </div>
            </div>
            
            <div id="toast" class="toast toast-top" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bi bi-info-circle-fill me-2 icon-info"></i>
                    <i class="bi bi-check-circle-fill me-2 icon-success"></i>
                    <i class="bi bi-exclamation-triangle-fill me-2 icon-warning"></i>
                    <i class="bi bi-bug-fill me-2 icon-danger"></i>
                    <strong class="me-auto toast-title">Information</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    This is a toast message.
                </div>
            </div>
            `
    }

    open(item){
        Modal.getOrCreateInstance("#edit%%(self.obName.title())%%Modal").show()
    }

    onApply(event){
        // TODO: Get all fields
        const data = {

        }
        // Call the server to store data
        call('/api/v1/%%(self.obName.lower())%%s/', 'POST', data).then((responseOk, responseFailure) => {
            console.log(responseOk, responseFailure)
            if(responseOk){
                // Raise an event to reload the list
                this.dispatchEvent(new CustomEvent('updateList'))
            }else{
                console.error(responseOk, responseFailure)
                this.openToast("Error during request", "danger", "Error")
            }
        })
    }

    /**
     * Display the toast with a message
     * 
     * @param {*} message 
     * @param {*} status String, one of danger | info | warning | success
     * @param {*} title 
     * @param {*} subtitle 
     */
    openToast(message, status = 'info', title= "", subtitle = ""){
        const toastElt = new Toast('#toast')
        toastElt.hide()
        toast.classList.remove('toast-danger', 'toast-info', 'toast-warning', 'toast-success')
        toast.classList.add('toast-'+status)
        toast.querySelector('.toast-title').innerHTML = title
        toast.querySelector('.toast-subtitle').innerHTML = subtitle
        toast.querySelector('.toast-body').innerHTML = message
        toastElt.show()
    }
}

window.customElements.define('app-%%(self.obName.lower())%%-edit', %%(self.obName.title())%%EditElement);