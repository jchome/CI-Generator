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
from kivy.adapters.listadapter import ListAdapter
from kivy.lang import Builder
from screens.customscreen import CustomScreen
from database.%%(self.obName.lower())%%datareader import %%(self.obName)%%DataReader

__all__ = ("List%%(self.obName)%%s", "List%%(self.obName)%%sApp")

Builder.load_string("""
[CustomListItem%%(self.obName)%%@SelectableView+BoxLayout]:
	orientation: 'horizontal'
	spacing: '10sp'
	padding: (sp(20), 0)
	size_hint_y: None
	height: '64sp'
	index: ctx.index
	canvas.after:
		Color:
			rgb: 0.5,0.5,0.5
		Line:
			rectangle: self.x,self.y+self.height,self.width,0
	
	
	# Icon of item
	ListItemButton:
		id: mainListItemButton
		canvas.before:
			Color:
				rgba: 1,1,1, 1
			Rectangle:
				source: "images/ic_action_star.png"
				pos: self.pos
				size: self.size
		size_hint_x: None
		width: '64sp'
		selected_color: 0,0,0, 0
		deselected_color: 1,1,1, 0
		background_color: 1,1,1, 0
		background_normal: ""
		background_down: ""
		
	ListItemButton:
		selected_color: 0,0,1, 0
		deselected_color: 1,1,1, 0
		background_color: 1,1,1, 0
		background_normal: ""
		background_down: ""
		
		halign: 'left'
		text_size: (self.width , None)
		color: [1,1,1, 1]
		text: '%s\\n[size=14sp][color=bfbfbf]%s[/color][/size]' % (ctx.text, ctx.subtext)
		markup: True
		font_size: '22sp'
		

""")

class List%%(self.obName)%%s(CustomScreen):
	
	def __init__(self, aName):
		super(List%%(self.obName)%%s, self).__init__()
		self.name = aName
		self.allItems = {}
		# prepare display
		self.setItems( {} )
		self.updateDisplay()
		self.%%(self.obName.lower())%% = None
		
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
		self.%%(self.obName.lower())%% = adapter.data[adapter.selection[0].parent.index]
		adapter.selection[0].deselect()
		
		nextScreen = self.manager.go_next()
		nextScreen.setItems( ... ) #TODO: ajouter les paramètres pour l'ecran suivant
		
		
	def setItems(self, data):
		self.allItems = data
		#self.logLabel.text = "count : %s" % len(self.allItems)
		self.updateDisplay()
		self.%%(self.obName.lower())%% = None
		
		
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
	
