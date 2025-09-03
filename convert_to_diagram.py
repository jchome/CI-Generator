#!/bin/env python3
# -*- coding: utf-8 -*-

## Usage : python .\convert_to_diagram.py .\objects\*.xml
## Will produce a file "out.dbml"

import sys
import xml.etree.ElementTree as ET
import glob
from unidecode import unidecode
import re
from datetime import datetime
import codecs

class DbObject:
    def __init__(self, filemane):
        tree_root = ET.parse(filemane).getroot()

        self.displayName = unidecode( tree_root.get("displayName") )
        self.shortName = tree_root.get("shortName")
        self.obName = tree_root.get("obName")
        self.description = self.displayName
        self.attributes = []
        for element in tree_root:
            if element.tag == "description" and element.text is not None:
                self.description += " - " + element.text.replace("'", "\\'")
            elif element.tag == "attribute":
                attr = DbAttribute(element)
                self.attributes.append(attr)

    def output(self):
        attr = '\n  '.join( list(map(lambda a: a.output(), self.attributes)) )
        settings = []
        if self.description is not None:
            settings.append( "note:'%s'" % self.description )
        
        return """Table %(shortName)s %(settings)s {
  %(attr)s
}""" % {'shortName': self.shortName, 
        'attr': attr,
        'settings': "" if len(settings) == 0 else "[" + ', '.join(settings) + "]"
       }


class DbAttribute:
    def __init__(self, node):
        self.id = node.get('id')
        self.name = unidecode( node.get('name') )
        self.nullable = node.get('nullable')
        self.isKey = node.get('isKey')
        self.autoincrement = node.get('autoincrement')
        self.type = node.get('type')
        if self.type[0:4].upper() == "ENUM":
            pattern = re.compile("'([^']+)':'([^']+)'")
            keys = []
            pos = 0
            m = pattern.search(self.type, pos)
            while m:
                pos = m.start() + 1
                #print(m.group(1), m.group(2))
                keys.append(m.group(1))
                m = pattern.search(self.type, pos)
            keys = list(map(lambda k: "'%s'" % k, keys))
            self.type = "ENUM(" + ','.join(keys) + ")"
        elif self.type[0:4].upper() == "FLAG":
            self.type = "TINYINT(1)"
        self.referencedObject = node.get('referencedObject')
        self.access = node.get('access')
        self.display = node.get('display')
        self.description = self.name
        for subnode in node:
            if subnode.tag == "description" and subnode.text is not None:
                self.description += " - " + unidecode(subnode.text.replace("'", "\\'"))
        
    def output(self):
        res = self.id + " " + self.type
        settings = []
        if self.isKey == "YES":
            settings.append( "primary key" )
        elif self.nullable == "NO":
            settings.append( "not null" )
        if self.description is not None:
            settings.append( "note:'%s'" % self.description )
        settingsStr = "" if len(settings) == 0 else " [" + ', '.join(settings) + "]"
        return res + settingsStr

if __name__ == "__main__":
    ouputFile = codecs.open("out.dbml", "w", "utf-8")
    
    ouputFile.write("## Use with https://dbdiagram.io/\n")
    ouputFile.write("## -- %s --\n\n" % datetime.now().strftime("%m/%d/%Y, %H:%M:%S") )
    objects = {}
    for i in range(1, len(sys.argv)):
        for file in glob.glob(sys.argv[i]):
            obj = DbObject(file)
            objects[obj.displayName] = obj
            ouputFile.write( obj.output() + "\n" )

        for obj_name, obj in objects.items():
            for attribute in obj.attributes:
                if attribute.referencedObject is not None:
                    ref_obj = objects[attribute.referencedObject]
                    ref_obj_key = next(a for a in ref_obj.attributes if a.isKey == "YES")
                    ouputFile.write("\nRef: %s" % obj.shortName + "." + attribute.id + " < " + ref_obj.shortName + "." + ref_obj_key.id)
    ouputFile.close()
