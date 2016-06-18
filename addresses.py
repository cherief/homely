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

new_feature = '{{ \n "type": "Feature", \n "id": "node/{0}", \n "properties": {{ \n \t "@id": "node/{0}", \n "building": "house", \n "name": "{1}", \n "url": "{2}", \n "@timestamp": "{3}" \n }}, \n "geometry": {{\n "type": "Point", \n "coordinates": [ \n {4}, \n {5} \n] \n \t }} \n }} '

output = output + preamble

node_number = 247985030




# iterate through suburbs
for suburb in suburb_list.keys(): #[0:3]:

    # get list of addresses
    address_list,links_list = f.get_properties_forsale(suburb_list[suburb])

    #print len(address_list),len(links_list)

    for a,name in enumerate(address_list):

        print name, suburb

        #latitude,longitude = f.get_location(name + ", " + suburb)
        latitude,longitude = 0.1,0.1
        if latitude != 0:
            node = str(node_number)
            node_number = node_number  + 1
            timestamp = "2008-02-18T13:18:14Z"
            property_link = links_list[a]
            f.get_property_details(property_link)

            new_line = new_feature.format(node,name,property_link, timestamp,longitude,latitude)

            # if a == len(address_list)-1:
            #     output = output + new_line # if the feature is the last don't add a comma
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
