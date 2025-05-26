#!/bin/env python3

import xml.etree.ElementTree as ET
import glob
import unidecode
import re

class DbObject:
    def __init__(self, filemane):
        tree_root = ET.parse(filemane).getroot()
        self.displayName = unidecode.unidecode(tree_root.get("displayName"))
        self.shortName = tree_root.get("shortName")
        self.obName = tree_root.get("obName")
        self.attributes = []
        for element in tree_root:
            if element.tag == "description":
                self.description = element.text
            if element.tag == "attribute":
                attr = DbAttribute(element)
                self.attributes.append(attr)

    def output(self):
        attr = '\n  '.join( list(map(lambda a: a.output(), self.attributes)) )
        return """Table %s{
  %s
}""" % (self.displayName, attr)


class DbAttribute:
    def __init__(self, node):
        self.id = node.get('id')
        self.name = node.get('name')
        self.nullable = node.get('nullable')
        self.isKey = node.get('isKey')
        self.autoincrement = node.get('autoincrement')
        self.type = node.get('type')
        if self.type[0:4] == "ENUM":
            pattern = re.compile("'([^']+)':'([^']+)'")
            keys = []
            pos = 0
            m = pattern.search(self.type, pos)
            while m:
                pos = m.start() + 1
                #print(m.group(1), m.group(2))
                keys.append(m.group(1))
                m = pattern.search(self.type, pos)
            self.type = "ENUM(" + ','.join(keys) + ")"
        self.referencedObject = node.get('referencedObject')
        self.access = node.get('access')
        self.display = node.get('display')
        for subnode in node:
            if subnode.tag == "description":
                self.description = subnode.text
        
    def output(self):
        res = self.id + " " + self.type
        if self.isKey == "YES":
            res += " [primary key]"
        if self.isKey == "NO" and self.nullable == "NO":
            res += " [not null]"
        return res

if __name__ == "__main__":
    objects = {}
    for file in glob.glob("./objects/*.xml"):
        obj = DbObject(file)
        objects[obj.displayName] = obj
        print( obj.output() )

    for obj_name, obj in objects.items():
        for attribute in obj.attributes:
            if attribute.referencedObject is not None:
                ref_obj = objects[attribute.referencedObject]
                ref_obj_key = next(a for a in ref_obj.attributes if a.isKey == "YES")
                print("\n Ref: %s" % obj.displayName + "." + attribute.id + " < " + ref_obj.displayName + "." + ref_obj_key.id)
