# !/usr/bin/python3
from supermarktconnector.jumbo import JumboConnector
from supermarktconnector.ah import AHConnector
import threading
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
jumbo()
# s1 = threading.Thread(target=jumbo, args=[])
# s2 = threading.Thread(target=ah, args=[])
# s1.start()
# s2.start()
# s1.join()
# s2.join()
