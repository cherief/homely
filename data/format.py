import numpy as np
import json

def format_busstops():
    """
    Source website: https://www.data.act.gov.au/Transport/Bus-Stops/22rs-ycdh
    Save as: busstops.json
    """

    output  = ""

    preamble = """{
      "type": "FeatureCollection",
      "copyright": "The data included in this document is from https://www.data.act.gov.au/Transport/Bus-Stops/22rs-ycdh",
      "timestamp": "2016-04-24T01:03:02Z",
      "features": [
                """

    postamble = '\n ] \n} '
    
    new_feature = '{{ \n "type": "Feature", \n "id": "node/{0}", \n "properties": {{ \n \t "@id": "node/{0}",  \n "name": "{1}", \n "type":"bus stop", \n "@timestamp": "{2}" \n }}, \n "geometry": {{\n "type": "Point", \n "coordinates": [ \n {3}, \n {4} \n] \n \t }} \n }} '
    output = output + preamble

    node_number = 257985030

    # open json file

    json_data = open("busstops.json").read()

    busstops = json.loads(json_data)

    busstop_list = []
    busstop_lat_list,busstop_long_list = [],[]

    for busstop in busstops["data"]:
        node = str(node_number)
        node_number = node_number  + 1
        timestamp = "2008-02-18T13:18:14Z"

        name = busstop[10]
        latitude = str(float(busstop[12][1]))
        longitude = str(float(busstop[12][2]))

        new_line = new_feature.format(node, name, timestamp, longitude, latitude)

        output = output + new_line + "\n,\n" 

    output = output + postamble

    # write geojson output to file
    f = open("busstops.geojson","w+")
    f.write(output)
    f.close()

format_busstops()