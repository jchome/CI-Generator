#!/usr/bin/python
# -*- coding: utf-8 -*-
import kivy
kivy.require('1.0.5')

from kivy.config import Config

# Samsung S4 : 1080x1920
Config.set('graphics', 'width', '450')
Config.set('graphics', 'height', '800')

__version__ = '1.5'
## install & run with 
# buildozer android debug deploy run

## see logs with
# adb logcat -s "python"

from kivy.app import App
from kivy.uix.screenmanager import ScreenManager, Screen, SlideTransition

### import des entités
#from clients import ClientsApp

class Welcome(Screen):

	def do_enter(self):
		self.manager.transition = SlideTransition(direction="left")
		self.manager.current = self.manager.next()
		### passer a l'écran clients
		#aJsonData = ...
		#self.manager.get_screen("Clients").setItems( aJsonData )


class WelcomeApp(App):
	
	def build(self):
		manager = ScreenManager()
		
		### ajout de l'instance de page d'accueil
		welcomeScreen = Welcome(name='Welcome')
		
		manager.add_widget(welcomeScreen)
		
		### ajout de la vue 'Clients'
		#app = ClientsApp()
		#app.load_kv()
		#clientsView = app.build()
		#manager.add_widget(clientsView)
		
		
		manager.transition = SlideTransition(direction="left")
		return manager

if __name__ == '__main__':
	WelcomeApp().run()

