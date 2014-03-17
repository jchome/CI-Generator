%[kind : controllers]
%[file : list%%(self.obName.lower())%%s.py]
%[path : screens/%%(self.obName.lower())%%sScreen]
#!/usr/bin/python
# -*- coding: utf-8 -*-
'''
This code is generated.
'''

###
# AFTER CODE GENRATION : use this code into the "main.py" file to insert a screen 
# from screens.%%(self.obName.lower())%%sScreen.list%%(self.obName.lower())%%s import List%%(self.obName)%%sApp
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

__all__ = ("List%%(self.obName)%%s", "List%%(self.obName)%%sApp")

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
		id: mainListItemButton
		canvas.before:
			Color:
				rgba: 1,0,0, 1
			Rectangle:
				pos: self.pos
				size: self.size
		halign: 'center'
		text_size: (self.width , None)
		color: (1,1,1, 1)
		text: "[ X ]"
		size_hint_x: None
		width: '80sp'
		font_size: '22sp'
		
	ListItemButton:
		selected_color: 0,0,1, 0
		deselected_color: 1,1,1, 0
		background_color: 1,1,1, 0
		background_normal: ""
		background_down: ""
		
		halign: 'left'
		text_size: (self.width , None)
		color: [1,1,1, 1]
		text: ctx.text +'\\n[size=14sp][color=bfbfbf]'+ ctx.subtext+'[/color][/size]'
		markup: True
		font_size: '22sp'
		

""")

class List%%(self.obName)%%s(Screen):
	allItems = None
	def __init__(self, aName):
		super(List%%(self.obName)%%s, self).__init__()
		self.name = aName
		# prepare display
		self.setItems([])
		self.updateDisplay()
		
	def updateDisplay(self):
		list_item_args_converter = \
			lambda row_index, obj: {'text': obj.%%(self.keyFields[0].dbName)%%,
									'subtext': "Lorem ipsum",
									'index': row_index,
									'id': "itemindex_%d" % row_index, 
									'is_selected': False,
									'size_hint_y': None,
									'height': 25}
		
		my_adapter = ListAdapter(data = self.allItems.itervalues(),
									args_converter=list_item_args_converter,
									selection_mode='single',
									allow_empty_selection=True,
									template='CustomListItem%%(self.obName)%%')
		
		my_adapter.bind(on_selection_change=self.item_changed)
		self.containerListView.adapter = my_adapter
		
	def item_changed(self, adapter, *args):
		if len(adapter.selection) == 0:
			return
		objectSelected = adapter.data[adapter.selection[0].parent.index]
		adapter.selection[0].deselect()
		
		#self.manager.transition = SlideTransition(direction="left")
		#self.manager.current = "name of the next screen"
		#self.manager.get_screen("name of the next screen").setItems( ... )
		
		
	def setItems(self, data):
		self.allItems = data
		#self.logLabel.text = "count : %s" % len(self.allItems)
		self.updateDisplay()
		
	def back(self):
		self.manager.transition = SlideTransition(direction="right")
		self.manager.current = 'Welcome'
		
	def newItem(self):
		pass

	def refresh(self):
		%%(self.obName.lower())%%Helper = %%(self.obName)%%DataReader()
		%%(self.obName.lower())%%Helper.refreshData()
		self.setItems(%%(self.obName.lower())%%Helper.getAllRecords())
	
class List%%(self.obName)%%sApp(App):
	screenName = 'List%%(self.obName)%%s'
	
	def build(self):
		return List%%(self.obName)%%s(self.screenName)
	