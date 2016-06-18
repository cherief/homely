

"""
Script to get homes for sale on allhomes.

# DONE - property url - property_url
# DONE - street address - street_address
# DONE - listing price - listing_price
# number of bedrooms - n_bedrooms
# number of bathrooms - n_bathrooms
# number of garages - n_garages
# DONE - property type - property type
# DONE - unimproved value - UV
# house size - house_size
# DONE - block size - block_size
# DONE - EER - EER

"""

__author__ = "cherief"
__version__ = "1.0"

import bs4
from bs4 import BeautifulSoup
import numpy as np
import requests
import pandas as pd
import datetime as dt
import datefinder
import httplib, urllib  # required for Pushover
import os
import sys

from geolocation.main import GoogleMaps 
from geolocation.distance_matrix.client import DistanceMatrixApiClient

api_file = open('api')
api = api_file.read()


google_maps = GoogleMaps(api_key=api)


def get_properties_forsale(url_suburb):
    """
    Returns a dictionary of properties (street address + link) for sale from allhomes.com.au in the requested suburb.
    """

    r = requests.get(url_suburb)

    soup = BeautifulSoup(r.text, 'html.parser')

    data = soup.find_all('tbody') 

    data_addresses = data[0].find_all('div',{'class':'listingThumbnailRow'})
    data_links = data[0].find_all('td',{'class':'listing_img'})

    all_addresses,all_links = [],[]

    for d in data_links:
        for a in d.find_all('a'):
            all_links.append(a['href'])

    for d in data_addresses:
        for a in d.find_all('img'):
            all_addresses.append(a['title'])

    return all_addresses,all_links

def get_property_details(property_link):

    r = requests.get(property_link)
    soup = BeautifulSoup(r.text, 'html.parser')



    data = soup.find_all('h2',{'class':'listing-price'})

    try:
        listing_price = data[0].get_text().replace('$','').replace('+','').replace(',','')
        if '-' in listing_price:
            listing_price = 0
        elif 'Auction' in listing_price:
            listing_price = 1
        elif 'negotiation' in listing_price:
            listing_price = 2
    except:
        listing_price = 0

    # get headers
    data1 = soup.find_all('td',{'class':'listing-briefstats-header'})

    # get values
    data2 = soup.find_all('td',{'class':'listing-briefstats-value'})


    block_size = None
    property_type = None
    unimproved_value = None
    EER = None

    for d,tmp in enumerate(data1):
        
        if 'Block Size' in data1[d].get_text():
            block_size = data2[d].get_text()
        elif 'Property Type' in data1[d].get_text():
            property_type = data2[d].get_text()
        elif 'Unimproved Value' in data1[d].get_text():
            unimproved_value = data2[d].get_text()
        # elif 'EER' in data3[d].get_text():
       
        else:
            #print data1[d].get_text(), data2[d].get_text()
            pass

    data3 = soup.find_all('td')

    for d,tmp in enumerate(data3[:-2]):
        if 'EER' in data3[d].get_text():
            EER = data3[d+1].get_text().replace(' ','').replace('\n','')


    r.close()

    print listing_price, '|', block_size,  '|', property_type, '|', unimproved_value, '|', EER




def get_location(address):
    """
    Uses Google API to convert from street address to latitude and longitude.
    """

    try:
        location = google_maps.search(location=address) # sends search to Google Maps.
        my_location = location.first() # returns only first location.

        return my_location.lat, my_location.lng
    except:
        print "Not a valid address."
        return 0,0




# lat: -35.26162  long: 149.145187


# if __name__ == "__main__":

#     # get path of main.py
#     if len(sys.argv) == 2:
#         path = str(sys.argv[1])
#     else:
#         path = ""

#     # get url
#     suburb = "Ainslie"
#     url_suburb = "http://www.allhomes.com.au/ah/act/sale-residential/ainslie/121474310"

#     all_links = get_links(url_suburb)


#     for l in get_links(url_suburb):
#         lat,lon = get_location(l + " " + suburb)
#         print l, lat, lon
#         print '-------------'


# http://www.nbnco.com.au/connect-home-or-business/check-your-address.html?address=1%20Hurrell%20St%20Forde%20ACT&type=home