# !/usr/bin/python3
from supermarktconnector.jumbo import JumboConnector
from supermarktconnector.ah import AHConnector
from pprint import pprint
import sys


def jumbo():
    connector = JumboConnector()
    jumboproducts = connector.search_products(
        query=sys.argv[1], size=30, page=0)
    print(jumboproducts)


def ah():
    connector = AHConnector()
    ahproducts = connector.search_products(query=sys.argv[1], size=30, page=0)
    print(ahproducts)


ah()
# jumbo()
