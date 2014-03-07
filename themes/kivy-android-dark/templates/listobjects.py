%[kind : controllers]
%[file : list%%(self.obName.lower())%%s.py]
%[path : %%(self.obName.lower())%%sScreen]
#!/usr/bin/python
# -*- coding: utf-8 -*-
'''
This code is generated.
'''

###
# AFTER CODE GENRATION : use this code into the "main.py" file to insert a screen 
# from %%(self.obName.lower())%%sScreen.list%%(self.obName.lower())%%s import List%%(self.obName)%%sApp
#
# app = List%%(self.obName)%%sApp()
# app.load_kv()
# %%(self.obName.lower())%%sView = app.build()
# manager.add_widget(%%(self.obName.lower())%%sView)
###

import kivy
kivy.require('1.0.5')

from kivy.app import App
from kivy.uix.screenmanager import Screen, SlideTransition
from kivy.adapters.listadapter import ListAdapter
from kivy.lang import Builder


Builder.load_string("""
[CustomListItem%%(self.obName)%%@SelectableView+BoxLayout]:
	orientation: 'horizontal'
	spacing: '10sp'
	padding: (sp(20), 0)
	size_hint_y: None
	height: '100sp'
	index: ctx.index
	canvas.after:
		Color:
			rgb: 0.5,0.5,0.5
		Line:
			rectangle: self.x,self.y+self.height,self.width,0
	
	
	# Text of item
	ListItemButton:
		canvas.before:
			Color:
				rgba: 1,0,0, 1
			Rectangle:
				pos: self.pos
				size: self.size
		selected_color: 0,0,0, 0
		deselected_color: 1,1,1, 0
		background_color: 1,1,1, 0
		background_normal: ""
		background_down: ""
		
		halign: 'center'
		text_size: (self.width , None)
		color: [1,1,1, 1]
		text: "[ X ]"
		size_hint_x: None
		width: '80sp'
		font_size: '22sp'

	BoxLayout:
		orientation: 'vertical'
		ListItemButton:
			selected_color: 0,0,0, 0
			deselected_color: 1,1,1, 0
			background_color: 1,1,1, 0
			background_normal: ""
			background_down: ""
			
			halign: 'left'
			text_size: (self.width , None)
			color: [1,1,1, 1]
			text: ctx.text
			#font_name: "fonts/Ubuntu-L.ttf"
			font_size: '22sp'
		Label:
			text: ctx.subtext
			valign: 'top'
			halign: 'left'
			text_size: self.size
			color: (1, 1, 1, 0.75)
			font_size: '14sp'

""")

class List%%(self.obName)%%s(Screen):
	allItems = None
	def __init__(self, ):
		super(List%%(self.obName)%%s, self).__init__()
		# prepare display
		self.setItems([])
		self.updateDisplay()
		
	def updateDisplay(self):
		list_item_args_converter = \
			lambda row_index, obj: {'text': self.allItems[obj]["%%(self.keyFields[0].dbName)%%"],
									'subtext': "Lorem ipsum",
									'index': row_index,
									'id': "itemindex_%d" % row_index, 
									'is_selected': False,
									'size_hint_y': None,
									'height': 25}
		
		my_adapter = ListAdapter(data = self.allItems,
									args_converter=list_item_args_converter,
									selection_mode='single',
									allow_empty_selection=True,
									template='CustomListItem%%(self.obName)%%')
		
		my_adapter.bind(on_selection_change=self.item_changed)
		self.containerListView.adapter = my_adapter
		
	def item_changed(self, adapter, *args):
		if len(adapter.selection) == 0:
			return
		objectId = adapter.data[adapter.selection[0].parent.index]
		objectSelected = self.allItems[objectId]
		# pour le graphisme, ne pas changer la couleur du bouton
		adapter.selection[0].deselect()
		
		#self.manager.transition = SlideTransition(direction="left")
		#self.manager.current = "name of the next screen"
		#self.manager.get_screen("name of the next screen").setItems( ... )
		
		
	def setItems(self, data):
		self.allItems = data
		#self.logLabel.text = "count : %s" % len(self.categories)
		self.updateDisplay()
		
	def back(self):
		self.manager.transition = SlideTransition(direction="right")
		self.manager.current = 'Welcome'
		
	def newItem(self):
		pass

	def refresh(self):
		pass
	
class List%%(self.obName)%%sApp(App):
		
	def build(self):
		screen = List%%(self.obName)%%s()
		screen.name = 'List%%(self.obName)%%s'
		return screen
	