function createChartTwo(e,o){var t={series:[{name:"This Day",data:[18,25,20,35,25,55,45,50,40]}],chart:{type:"area",width:"100%",height:360,sparkline:{enabled:!1},toolbar:{show:!1},padding:{left:0,right:0,top:0,bottom:0}},dataLabels:{enabled:!1},stroke:{curve:"smooth",width:4,colors:[o],lineCap:"round"},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:1,position:"back",xaxis:{lines:{show:!1}},yaxis:{lines:{show:!0}},row:{colors:void 0,opacity:.5},column:{colors:void 0,opacity:.5},padding:{top:-30,right:0,bottom:-10,left:0}},fill:{type:"gradient",colors:[o],gradient:{shade:"light",type:"vertical",shadeIntensity:.5,gradientToColors:[`${o}00`],inverseColors:!1,opacityFrom:.6,opacityTo:.3,stops:[0,100]}},markers:{colors:[o],strokeWidth:3,size:0,hover:{size:10}},xaxis:{labels:{show:!1},categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}}},yaxis:{labels:{show:!1}},tooltip:{x:{format:"dd/MM/yy HH:mm"}}};new ApexCharts(document.querySelector(`#${e}`),t).render()}createChartTwo("recent-orders","#487fff");var options={series:[30,25],colors:["#FF9F29","#487FFF"],labels:["Female","Male"],legend:{show:!1},chart:{type:"donut",height:230,sparkline:{enabled:!0},margin:{top:0,right:0,bottom:0,left:0},padding:{top:0,right:0,bottom:0,left:0}},stroke:{width:0},dataLabels:{enabled:!1},responsive:[{breakpoint:480,options:{chart:{width:200},legend:{position:"bottom"}}}]};(chart=new ApexCharts(document.querySelector("#statisticsDonutChart"),options)).render();var chart;options={series:[{name:"Net Profit",data:[2e4,16e3,14e3,25e3,45e3,18e3,28e3,11e3,26e3,48e3,18e3,22e3]},{name:"Revenue",data:[15e3,18e3,19e3,2e4,35e3,2e4,18e3,13e3,18e3,38e3,14e3,16e3]}],colors:["#487FFF","#FF9F29"],labels:["Active","New","Total"],legend:{show:!1},chart:{type:"bar",height:250,toolbar:{show:!1}},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:4,position:"back"},plotOptions:{bar:{borderRadius:4,columnWidth:10}},dataLabels:{enabled:!1},stroke:{show:!0,width:2,colors:["transparent"]},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]},yaxis:{categories:["0","5000","10,000","20,000","30,000","50,000","60,000","60,000","70,000","80,000","90,000","100,000"]},fill:{opacity:1,width:18}};(chart=new ApexCharts(document.querySelector("#paymentStatusChart"),options)).render(),$("#world-map").vectorMap({map:"world_mill_en",backgroundColor:"transparent",borderColor:"#fff",borderOpacity:.25,borderWidth:0,color:"#000000",regionStyle:{initial:{fill:"#D1D5DB"}},markerStyle:{initial:{r:5,fill:"#fff","fill-opacity":1,stroke:"#000","stroke-width":1,"stroke-opacity":.4}},markers:[{latLng:[35.8617,104.1954],name:"China : 250"},{latLng:[25.2744,133.7751],name:"AustrCalia : 250"},{latLng:[36.77,-119.41],name:"USA : 82%"},{latLng:[55.37,-3.41],name:"UK   : 250"},{latLng:[25.2,55.27],name:"UAE : 250"}],series:{regions:[{values:{US:"#487FFF ",SA:"#FF9F29",AU:"#45B369",CN:"#F86624",GB:"#487FFF"},attribute:"fill"}]},hoverOpacity:null,normalizeFunction:"linear",zoomOnScroll:!1,scaleColors:["#000000","#000000"],selectedColor:"#000000",selectedRegions:[],enableZoom:!1,hoverColor:"#fff"});