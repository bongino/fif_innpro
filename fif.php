<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
        <title>Layer List</title>
        <link rel="stylesheet" href="http://js.arcgis.com/3.11/dijit/themes/claro/claro.css">
        <link rel="stylesheet" href="http://js.arcgis.com/3.11/esri/css/esri.css">
        <script src="http://js.arcgis.com/3.11/"></script>
        <script>
            var map, titik;
            var visible = [];
            require([
                "dojo/parser", "esri/map", "esri/layers/ArcGISDynamicMapServiceLayer",
                "dojo/dom", "dojo/on", "dojo/query", "dojo/_base/array", "dojo/parser",
                "esri/layers/FeatureLayer", "dojo/dom-construct", "esri/InfoTemplate", "esri/dijit/Legend", "esri/tasks/BufferParameters",
                "esri/graphicsUtils", "esri/tasks/GeometryService", "esri/geometry/webMercatorUtils", "esri/config",
                "esri/symbols/SimpleFillSymbol", "esri/Color", "dojo/_base/array", "esri/graphic",
                "dojo/domReady!"
            ], function(
                    parser, Map, ArcGISDynamicMapServiceLayer,
                    dom, on, query, arrayUtils, parser, FeatureLayer, domConstruct, InfoTemplate,
                    Legend, BufferParameters, graphicsUtils, GeometryService, webMercatorUtils, config, SimpleFillSymbol, Color, array, Graphic
                    ) {
                parser.parse();


                map = new Map('map', {
                    basemap: "gray",
                    center: [106.8128334, -6.1796276], //longitude, latitude
                    zoom: 15,
                    sliderPosition: "top-right",
                    sliderStyle: "small"
                });
                titik = new FeatureLayer("http://innmap.co.id:6080/arcgis/rest/services/fif/poitest_1/FeatureServer/0");
                map.addLayer(titik);
                //add the legend
                var legend = new Legend({
                    map: map,
                    layerInfos: [
                        {
                            layer: titik,
                            title: "FIF Legend's"
                        }
                    ]
                }, "legend");

                legend.startup();


                // end add legend

                function bufferTitik() {
                    config.defaults.geometryService = new GeometryService("http://sampleserver6.arcgisonline.com/arcgis/rest/services/Utilities/Geometry/GeometryServer");
                    config.defaults.io.proxyUrl = "/proxy/";
                    alert("hii");
                    var featureLayer = map.getLayer(map.graphicsLayerIds[0]);
                    var bufferGeodesic = new BufferParameters();
                    bufferGeodesic.geometries = graphicsUtils.getGeometries(featureLayer.graphics);
                    bufferGeodesic.distances = [0.2];
                    bufferGeodesic.outSpatialReference = map.spatialReference;
                    bufferGeodesic.unit = GeometryService.UNIT_KILOMETER;
                    bufferGeodesic.geodesic = true;
                    config.defaults.geometryService.buffer(bufferGeodesic, function(geometries) {
                        var symbol = new SimpleFillSymbol();
                        symbol.setColor(new Color([100, 100, 100, 0.25]), 2);
                        array.forEach(geometries, function(geometry) {
                            map.graphics.add(new Graphic(geometry, symbol));
                        });
                    });

                }

                function titik() {
                    alert("hi");
                }

                on(dom.byId("buffer"), "click", bufferTitik);

            });

            // buffer titik
//                function bufferTitik(map) {
////                    alert(id);
////                    titik = new FeatureLayer("http://innmap.co.id:6080/arcgis/rest/services/fif/poitest/FeatureServer/2");
////                     map.addLayer(titik);
//                    //Pull first layer from the webmap and use it as input for the buffer operation
//                    var featureLayer = map.getLayer(map.graphicsLayerIds[0]);
//                    alert(map.graphicsLayerIds[0]);
//                    alert(featureLayer.graphics);
//                    var bufferGeodesic = new esri.tasks.BufferParameters();
//                    //get all geometries from the featurelayer and set on bufferparameter.
//                    bufferGeodesic.geometries = esri.getGeometries(featureLayer.graphics);
////                    alert(bufferGeodesic.geometries);
//                    bufferGeodesic.distances = [0.2];
//                    bufferGeodesic.outSpatialReference = map.spatialReference;
//                    bufferGeodesic.unit = esri.tasks.GeometryService.UNIT_KILOMETER;
//
////                    //the 10.1 geometry service magic sauce;  
////                    // buffers will have correct distance no matter what the spatial reference of the map is.
//                    bufferGeodesic.geodesic = true;
////                    //union all buffers that interesect each other.
////                    bufferGeodesic.unionResults = true;
//                    esri.config.defaults.geometryService.buffer(bufferGeodesic, function(geometries) {
//                        //when buffer is done set up renderer and add each geometry to the maps graphics layer as a graphic
//                        var symbol = new esri.symbol.SimpleFillSymbol();
//                        symbol.setColor(new esri.Color([255,0,0,0.65]),2);
////                        symbol.setOutline(null);
//                       dojo.array.forEach(geometries, function(geometry) {
//                            map.graphics.add(new esri.Graphic(geometry, symbol));
//                        });
//                    });
//
//                }
            // end buffer titik 
        </script>
        <style>
            body{
                margin: 0;
            }

            #container{

            }
            #panel{
                background: none repeat scroll 0 0 #fff;
                display: block;
                position: absolute;
                top: 0;
                height: 100%;
                width: 200px;
            }
            #map{
                height: 100%;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="map">
            </div> 
            <div id="panel">
                <h3>Layer List :</h3><span id="layer_list"></span><br>

                <div id="legend"></div>
                <div><a href="#" id="buffer">Buffer</a></div>
            </div>
        </div>
    </body>
</html>