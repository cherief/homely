import numpy as np
import json
import sqlite3
import os

json_data = open("addresses.geojson").read()
addresses = json.loads(json_data)



"""
 type           | "type": "Feature", 
 id             | "id": "node/247985030", 
                    "properties": { 
                    "@id": "node/247985030", 
 propertytype   | "propertytype": "Apartment", 
 name           | "name": "72D / 58 Wattle Street", 
 url            | "url": "http://www.allhomes.com.au/thisisasite", 
 address        | "address": "72D / 58 Wattle Street", 
 price          | "price": "$269,000+", 
 bedrooms       | "bedrooms": "3", 
 bathrooms      | "bathrooms": "3", 
 garages        | "garages": "1", 
 UV             | "UV": "$7,013,000", 
 EER            | "EER": "3.0", 
 blocksize      | "blocksize": "1.12 Hectares (approx)", 
 housesize      | "housesize": "50",
 timestamp      | "@timestamp": "2008-02-18T13:18:14Z" 
                     }, 
                     "geometry": {
                     "type": "Point", 
                     "coordinates": [ 
longitude       | 149.1271529, 
latitude        |  -35.2550338 
"""


def dinner():

    os.system('rm addresses.db')



    # create table
    conn = sqlite3.connect('addresses.db')
    c = conn.cursor()

    create_table = 'CREATE TABLE ' + 'alladdresses' + ' (num INT, propertyid TEXT, propertytype TEXT, name TEXT, url TEXT, address TEXT, listingprice INT, auction TEXT, bedrooms INT, bathrooms INT, garages INT, UV INT, EER DECIMAL, blocksize DECIMAL, housesize DECIMAL, timestamp TEXT, longitude FLOAT, latitude FLOAT )'

    c.execute(create_table)

    num = 0

    for i,address in enumerate(addresses["features"]):


        propertytype = address["properties"]["propertytype"]
        name = address["properties"]["name"]
        propertyid = address["id"]
        url = address["properties"]["url"]
        addressname = address["properties"]["address"]
        price = address["properties"]["price"]
        if '$' in price:
            price = price.replace("$","").replace(",","").replace("+","").replace(" ","").replace(".00","")
            if '-' in price:
                listingprice = int(price.split('-')[-1])
            else:
                listingprice = int(price)
            auction = 'no'
        else:
            listingprice = 0
            auction = price

        print listingprice, auction

        bedrooms = address["properties"]["bedrooms"]
        bathrooms = address["properties"]["bathrooms"]
        garages = address["properties"]["garages"]
        UV = address["properties"]["UV"]
        if '$' in UV:
            UV = UV.replace("$","").replace(",","").replace("+","")
        EER = address["properties"]["EER"]
        blocksize = address["properties"]["blocksize"]
        housesize = address["properties"]["housesize"]
        timestamp = address["properties"]["@timestamp"]
        longitude, latitude = address["geometry"]["coordinates"]
        longitude = str(longitude).replace(' ','')
        latitude = str(latitude).replace(' ','')

        #insert_table = "INSERT INTO alladdresses VALUES ('" + str(num)+ "','" + str(propertyid) + "','" + propertytype + "','" + name + "','" + str(url) + "','" + addressname + "','" + listingprice + "','" + auction + "','" + str(bedrooms)  + "','" + str(bathrooms) + "','" + str(garages) + "','" + str(UV) + "','" + str(EER) + "','" + str(blocksize) + "','" + str(housesize) + "','" + str(timestamp) + "','" + str(longitude) + "','" + str(latitude) + "')" 

        insert_table = "INSERT INTO alladdresses VALUES ('" + str(num)+ "','" + str(propertyid) + "','" + propertytype + "','" + name + "','" + str(url) + "','" + addressname + "','" + str(listingprice) + "','" + auction + "','" + str(bedrooms)  + "','" + str(bathrooms) + "','" + str(garages) + "','" + str(UV) + "','" + str(EER) + "','" + str(blocksize) + "','" + str(housesize) + "','" + str(timestamp) + "','" + str(longitude) + "','" + str(latitude) + "')" 

        print insert_table

        c.execute(str(insert_table))

        num = num + 1

    conn.commit()

    with open('addresses.sql', 'w') as f:
        for line in conn.iterdump():
            f.write('%s\n' % line)


dinner()
