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

class CustomScreenManager(ScreenManager):
	def __init__(self, **kwargs):
		super(ScreenManager, self).__init__(**kwargs)
		self.allScreens = []
		self.screenIndex = 0
		Window.bind(on_keyboard=self.hook_keyboard)
		
	def hook_keyboard(self, window, key, *largs):
		if key == 27: # BACK
			#print("BACK")
			return self.back()
		elif key in (282, 319): # SETTINGS
			# Irrelevant code
			print("SETTINGS")
	
	def add_screen(self, aScreen):
		self.allScreens.append(aScreen.name)
		self.add_widget(aScreen)
		
	def next(self):
		self.screenIndex = self.screenIndex + 1
		self.transition = SlideTransition(direction="left")
		self.current = self.allScreens[self.screenIndex]
		return self.current_screen
	
	def back(self):
		if self.screenIndex == 0:
			return False
		self.screenIndex = self.screenIndex - 1
		self.transition = SlideTransition(direction="right")
		self.current = self.allScreens[self.screenIndex]
		self.current_screen.postback()
		return True
	
class Welcome(Screen):

	def do_enter(self):
		self.manager.goNext()
		### passer a l'écran clients
		#aJsonData = ...
		#self.manager.get_screen("Clients").setItems( aJsonData )


class WelcomeApp(App):
	
	def build(self):
		manager = CustomScreenManager()
		
		### ajout de l'instance de page d'accueil
		welcomeScreen = Welcome(name='Welcome')
		
		manager.add_screen(welcomeScreen)
		
		### ajout de la vue 'Clients'
		#app = ClientsApp()
		#app.load_kv()
		#clientsView = app.build()
		#manager.add_widget(clientsView)
		
		
		manager.transition = SlideTransition(direction="left")
		return manager

if __name__ == '__main__':
	WelcomeApp().run()

