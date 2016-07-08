# create addresses.geojson from a list of addresses

import functions as f




suburb_list = { "Ainslie": "http://www.allhomes.com.au/ah/act/sale-residential/ainslie/121474310",
            "Dickson" : "http://www.allhomes.com.au/ah/act/sale-residential/dickson/121460610",
            "Downer" : "http://www.allhomes.com.au/ah/act/sale-residential/downer/121459510",
            "Duffy" : "http://www.allhomes.com.au/ah/act/sale-residential/duffy/121500110",
            "Lyneham" : "http://www.allhomes.com.au/ah/act/sale-residential/lyneham/121479010",
            "North Lyneham" : "http://www.allhomes.com.au/ah/act/sale-residential/north-lyneham/121936210",
            "Turner" : "http://www.allhomes.com.au/ah/act/sale-residential/turner/121468510"
        }

output  = ""

preamble = """{
  "type": "FeatureCollection",
  "generator": "overpass-turbo",
  "copyright": "The data included in this document is from www.openstreetmap.org. The data is made available under ODbL.",
  "timestamp": "2016-04-24T01:03:02Z",
  "features": [
            """

postamble = '\n ] \n} '

new_feature = '{{ \n "type": "Feature", \n "id": "node/{0}", \n "properties": {{ \n \t "@id": "node/{0}", \n "propertytype": "{1}", \n "name": "{2}", \n "url": "{3}", \n "address": "{4}", \n "price": "{5}", \n "bedrooms": "{6}", \n "bathrooms": "{7}", \n "garages": "{8}", \n "UV": "{9}", \n "EER": "{10}", \n "blocksize": "{11}", \n "housesize": "{12}",\n "@timestamp": "{13}" \n }}, \n "geometry": {{\n "type": "Point", \n "coordinates": [ \n {14}, \n {15} \n] \n \t }} \n }} '
output = output + preamble

node_number = 247985030




# iterate through suburbs
for suburb in suburb_list.keys(): #[0:3]:

    # get list of addresses
    address_list,links_list = f.get_properties_forsale(suburb_list[suburb])

    #print len(address_list),len(links_list)

    for a,address in enumerate(address_list):

        print address, suburb

        latitude,longitude = f.get_location(address + ", " + suburb + ", Australia")
        if latitude != 0:
            node = str(node_number)
            node_number = node_number  + 1
            timestamp = "2008-02-18T13:18:14Z"
            property_link = links_list[a]
            price, blocksize, propertytype, UV, EER = f.get_property_details(property_link)

            # temp values
            #propertytype = "house"
            name = address.replace(", Australia","")
            url = "http://www.allhomes.com.au/thisisasite"
            #price = "$588,000"
            bedrooms = 5
            bathrooms = 2
            garages = 2
            #UV = "$499,000"
            #EER = "2.3"
            #blocksize = 788
            housesize = 50
            #------------- 

            new_line = new_feature.format(node,propertytype,name,url,address,price,bedrooms,bathrooms,garages,UV,EER,blocksize,housesize,timestamp,longitude,latitude)

            # if a == len(address_list)-1:
            #    output = output + new_line # if the feature is the last don't add a comma
            # else:
            output = output + new_line + "\n,\n" 


        print '-------------------'
# remove last line?

output = output + postamble


#print output



# write geojson output to file
f = open("addresses.geojson","w+")
f.write(output)
f.close()
