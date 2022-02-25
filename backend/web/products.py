#!/usr/bin/python
from supermarktconnector.jumbo import JumboConnector
import sys

connector = JumboConnector()
print(connector.search_products(query=sys.argv[1], size=1, page=0))