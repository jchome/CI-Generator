#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# coding: utf-8

"""
Copyright (C) 2011 julien CORON - http://julien.coron.free.fr

-- Licence GPL --

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

"""


"""
generate : produit des fichier php pour codeIgniter

Syntaxe:
./generate.py

"""

import sys, os, glob, string, configparser, re, codecs
from code import InteractiveInterpreter, InteractiveConsole
import traceback, inspect
from shutil import copyfile

from objects import CIObject

class TemplateFileReader:
	def __init__(self):
		self.fileOut = ""
		self.kind = ""
		self.filePath = ""
		self.segments = []
		self.templateFilename = ""

	def readFile(self, templateFilename):
		#f = codecs.open(templateFilename, 'r', sys.getfilesystemencoding())
		#f = codecs.open(templateFilename, 'r', encoding='utf-8')
		f = open(templateFilename, encoding='utf-8')
		self.templateFilename = templateFilename
		#print ("templateFilename : %s" % self.templateFilename)

		# detection des infos meta sur les premieres lignes
		regexpMetaSpliter = re.compile('^%\[\s*(?P<key>.+)\s*:\s*(?P<value>.+)\s*\]\s*$')
		rawContent = u""
		metaIfFinished = False
		metaInfos = {}

		for line in f:
			#DEBUG
			#print("---%s" % line)
			matchGroupMeta = regexpMetaSpliter.match(line)
			if matchGroupMeta and not metaIfFinished:
				#DEBUG print (matchGroupMeta.groupdict())
				metaInfos[matchGroupMeta.groupdict()['key'].strip()] = matchGroupMeta.groupdict()['value'].strip()
			else:
				#DEBUG if not metaIfFinished:
				#DEBUG 	print (line)
				metaIfFinished = True
				rawContent += line
		#DEBUG 
		#print (metaInfos)
		if 'file' in metaInfos :
			self.fileOut = self.extractSegments(metaInfos['file'])
		else:
			self.fileOut = ""

		if 'kind' in metaInfos :
			self.kind = metaInfos['kind']
		else:
			self.kind = ""

		if 'path' in metaInfos :
			self.filePath = self.extractSegments(metaInfos['path'])
		else:
			self.filePath = ""

		self.segments = self.extractSegments(rawContent.encode('utf-8'))

	def extractSegments(self, rawContent):
		# recuperaton des segments de code
		wasCode = False
		segments = []
		## Python3 compatibility
		if type(rawContent) == bytes:
			rawContent = rawContent.decode("utf-8")

		for item in (rawContent).split("%%"):
			#print("%s" % item )
			if wasCode:
				if re.match("\(.*\)", item):
					segments.append( PythonLine(item, self) )
					#DEBUG print ("PythonLine : %s" % item)
				else:
					segments.append( PythonSegment(item, self) )
					#DEBUG print ("PythonSegment : %s" % item)
			else:
				segments.append( StringSegment(item) )
			wasCode = not wasCode
		return segments


	def generateSegmentsFor(self, structure, databaseName = ""):
		return self.generateSegmentObjectFor(self.segments, structure, databaseName)

	def generateSegmentObjectFor(self, segmentArray, structure, databaseName = ""):
		content = u""
		for segment in segmentArray:
			## encoding to UTF-8
			content += segment.toString(structure, databaseName)
			
		return content


class StringSegment:
	def __init__(self, data):
		try:
			self.data = str(data)
		except Exception as e :
			print("XXXXXXXXXXXXXXXX")
			print(data)
			print("XXXXXXXXXXXXXXXX")
			raise e

	def toString(self, structure, databaseName = ""):
		return self.data

class PythonSegment:
	def __init__(self, data, aTemplateFileReader):
		try:
			self.data = str(data.strip())
		except Exception as e :
			print("XXXXXXXXXXXXXXXX")
			print(data)
			print("XXXXXXXXXXXXXXXX")
			raise e

		self.template = aTemplateFileReader
	
	def toString(self, structure, databaseName):
		filename='<input>'
		symbol='single'
		localVars = {"self" : structure, "DATABASE": databaseName, "RETURN" : ""}
		inter = InteractiveInterpreter(localVars)
		
		#if isinstance(source, types.UnicodeType):
		#    import IOBinding
		#    try:
		#        source = source.encode(IOBinding.encoding)
		#    except UnicodeError:
		console = InteractiveConsole(localVars, filename)

		try:
			code_object = compile(self.data.encode('ascii','ignore'), '<string>', 'exec')
			exec(code_object, localVars)
		except Exception as e :
			print("-  ERR --%s---------------------------------------" % (self.template.templateFilename) )
			InteractiveInterpreter.showsyntaxerror(console, filename)
			frames = inspect.trace()
			lineNumber = frames[1][2]
			print ("At line %s" % lineNumber)
			print ("- /ERR -----------------------------------------")

			print ("-  CODE -----------------------------------------")
			lines = self.data.split('\n')
			for i in range(0,lineNumber):
				print(lines[i])
			print("^"*20)
			
			print ("- /CODE -----------------------------------------")
			print ("")
					
		return localVars["RETURN"]

class PythonLine:
	def __init__(self, data, aTemplateFileReader):
		self.data = data.strip() #unicode(data).strip()
		self.template = aTemplateFileReader
	
	def toString(self, structure, databaseName = ""):
		result = u""
		try:
			result = eval(self.data, {"self" : structure, "DATABASE": databaseName} )
		except Exception as e:
			print ("ERROR while executing this code:")
			print ("-  ERR --Kind:%s---------------------------------------" % (self.template.kind) )
			print ("------------------------------------------------")
			print (self.data)
			print ("- /ERR -----------------------------------------")
		
		return result




def generateTemplates(rootFiles, readerTemplates, kind, databaseName):
	# generation du fichier a partir du template
	if not kind in readerTemplates:
		print ("No kind <%s>." % kind)
		return
	print ("  Generating files of kind <%s>:" % kind)
	for reader in readerTemplates[kind]:
		myDirectory = os.path.join(rootFiles, reader.generateSegmentObjectFor(reader.filePath, structure, databaseName) )
		if not os.path.exists(myDirectory):
			os.makedirs(myDirectory)
		filename = os.path.join(myDirectory, reader.generateSegmentObjectFor(reader.fileOut, structure, databaseName) )
		baseFilename = os.path.basename(filename)
		## Desactivate diff
		backupFilename = filename
		#destinationFolder = os.path.join( os.path.dirname(filename), '.dd')
		#backupFilename = os.path.join( destinationFolder, baseFilename)
		#diffFilename = backupFilename + ".diff"

		#if not os.path.exists(destinationFolder):
		#	os.makedirs(destinationFolder)

		#####################################
		# 1. DIFF PROCESS
		if os.path.exists(filename):
			#os.system("diff -u %(backupFilename)s %(filename)s > %(diffFilename)s" % {
			#	"filename":filename, 
			#	"backupFilename":backupFilename, 
			#	"diffFilename":diffFilename} )
			pass
		# /DIFF PROCESS
		#####################################
		
		
		#####################################
		# 2. GENERATION PROCESS
		# (in the backup file)
		content = reader.generateSegmentsFor(structure, databaseName)
		file = open(backupFilename,'w')
		file.write( "%s" % content )
		file.close()
		#/ GENERATION PROCESS
		#####################################
		


		#####################################
		# 3. MERGE PROCESS
		#if os.path.exists(diffFilename):
		#	os.system("patch %(backupFilename)s -i %(diffFilename)s -o %(filename)s" % {
		#		"filename":filename, 
		#		"backupFilename":backupFilename, 
		#		"diffFilename":diffFilename} )
		#else:
		# 3.bis -- or copy as the real true file, if no merge to do
		#	copyfile(backupFilename, filename)

		#/ MERGE PROCESS
		#####################################
		



		print ("    File <%s> successfully generated:" % filename)

if __name__ == '__main__':	

	# lecture du fichier de config
	config = configparser.ConfigParser()
	config.read_file(open('theme.cfg'))
	theme = config.get('global', 'theme').strip()
	CIRootFiles = config.get('generation', 'outDirFor_Classes').strip()
	generateObjects = config.get('generation','generate').strip()
	databaseName = config.get('generation', 'table_prefix').strip()

	# import du theme
	print ("Using theme <"+ theme +">...")

	# découpage de generateObjects en liste d'items à générer
	kindsToGenerate = []
	if generateObjects.find("all") > -1:
		kindsToGenerate = "helpers,controllers,views,subViews,baseModels,models,sql,lang,unitTest,json,js,doc,spec,conf".split(",")
	else:
		for item in generateObjects.split(","):
			kindsToGenerate.append(item.strip())


	if len(sys.argv) < 2:
		print ("Syntax : " + sys.argv[0] + " <fileObject0.xml> [fileObject1.xml]")
		sys.exit(1)
	
	pathOfScript = os.path.dirname(os.path.realpath(sys.argv[0]))
	
	# recuperation de tous les fichiers template
	allTemplates = {}
	for templateFilename in glob.glob(os.path.join(pathOfScript, "themes", theme, "templates","*.*")) + glob.glob(os.path.join(pathOfScript, "themes", theme, "templates","*","*.*")):
		#DEBUG print (">>>", templateFilename)
		reader = TemplateFileReader()
		reader.readFile(templateFilename)
		if reader.kind != "":
			if not reader.kind in allTemplates :
				allTemplates[reader.kind] = []
			allTemplates[reader.kind].append(reader)
			

	i = 1
	while i < len(sys.argv):
		aFilename = sys.argv[i]

		print ("-Object : " + aFilename)

		structure = CIObject()
		structure.fromXML(aFilename)

		for kind in kindsToGenerate:
			# générer les fichiers de template
			generateTemplates(CIRootFiles, allTemplates, kind, databaseName)

		i += 1

	print ("Done.")
	sys.exit(0)


