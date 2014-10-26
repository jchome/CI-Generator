#!/usr/bin/python
# -*- coding: utf-8 -*-
import kivy
kivy.require('1.0.5')

from kivy.config import Config

# Samsung S4 : 1080x1920
Config.set('graphics', 'width', '450')
Config.set('graphics', 'height', '800')

__version__ = '0.1'
## install & run with 
# buildozer android debug deploy run

## see logs with
# adb logcat -s "python"

from kivy.app import App
from kivy.uix.screenmanager import SlideTransition
from screens.customscreen import CustomScreenManager, CustomScreen

from screens.clientsScreen.listlocalitys import ListClientsApp
from database.clientdatareader import ClientDataReader

	
class Welcome(CustomScreen):

	def do_enter(self):
		self.manager.go_next()
		### passer a l'écran clients
		clientHelper = ClientDataReader()
		self.manager.get_screen("Clients").set_items( clientHelper.getAllRecords() )


class WelcomeApp(App):
	
	def build(self):
		manager = CustomScreenManager()
		
		### ajout de l'instance de page d'accueil
		welcomeScreen = Welcome(name='Welcome')
		
		manager.add_screen(welcomeScreen)
		
		### EXEMPLE : ajout de la vue 'ListClients'
		app = ListClientsApp()
		app.load_kv()
		clientsView = app.build()
		manager.add_screen(clientsView)
		
		manager.transition = SlideTransition(direction="left")
		return manager

if __name__ == '__main__':
	WelcomeApp().run()

