#!/usr/bin/python
# -*- coding: utf-8 -*-


import json
import urllib2
import sqlite3


class DataReader():
	database = None
	tablename = None
	fields = []
	
	def __init__(self, aTablename, fieldsArray):
		self.database = 'database.sdb'
		self.tablename = aTablename
		self.fields = fieldsArray
		
	def purgeTable(self):
		deleteStatment = "delete from "+self.tablename
		con = None
		try:
			con = sqlite3.connect(self.database)
			cur = con.cursor()
			print "Database intialisation..."
			cur.execute(deleteStatment);
			con.commit()
		except Error, e:
			print "Error with database: %s" % e
			return False
		finally:
			if con:
				con.close()

	def insertData(self, json_data):
		try:
			con = sqlite3.connect(self.database)
			cur = con.cursor()
			
			fieldsComaSep = ', '.join(self.fields)
			questionMarks = ', '.join(["?"]*len(self.fields))
			insertStatment = "INSERT INTO "+self.tablename+"("+fieldsComaSep+") VALUES("+questionMarks+")"
			
			print "Data insertion..."
			for object in json_data.values():
				objectAsTuple = []
				i=0
				for fieldName in self.fields:
					objectAsTuple.insert(i,object[ fieldName ])
					i = i+1
				cur.execute(insertStatment, tuple(objectAsTuple) )
			con.commit()

		except sqlite3.Error, e:
			print "Error with database: %s" % e
			return False
		finally:
			if con:
				con.close()
			print "ok"
		return True

	def __getRecords(self, statment, data = None):
		allRecords = {}
		try:
			con = sqlite3.connect(self.database)
			cur = con.cursor()
			if data is None:
				cur.execute(statment)
			else:
				cur.execute(statment, data)
			rows = cur.fetchall()
			for row in rows:
				aRecord = {}
				i=0
				for fieldName in self.fields:
					aRecord[ fieldName ] = row[i]
					i = i+1
				allRecords[row[0]] = aRecord
		except sqlite3.Error, e:
			print "Error with database: %s" % e
		finally:
			if con:
				con.close()
		return allRecords

	def getAllRecords(self):
		fieldsComaSep =  ', '.join(self.fields)
		return self.__getRecords("SELECT "+fieldsComaSep+" FROM "+self.tablename)

	def getAllRecordsEquals(self, aFieldname, aValue):
		fieldsComaSep =  ', '.join(self.fields)
		return self.__getRecords("SELECT "+fieldsComaSep+" FROM "+self.tablename + " where "+aFieldname+ " = ?", (aValue,))
		
class JsonRetriever():
	def retrieveFromUrl(self, urlForAllEntities):
		data = urllib2.urlopen(urlForAllEntities).read()
		json_data = json.loads(data)
		return json_data
