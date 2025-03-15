var options={series:[{name:"This month",data:[0,48,20,24,6,33,30,48,35,18,20,5]}],chart:{height:264,type:"line",toolbar:{show:!1},zoom:{enabled:!1}},dataLabels:{enabled:!1},stroke:{curve:"smooth",colors:["#487FFF"],width:4},markers:{size:0,strokeWidth:3,hover:{size:8}},tooltip:{enabled:!0,x:{show:!0},y:{show:!1},z:{show:!1}},grid:{row:{colors:["transparent","transparent"],opacity:.5},borderColor:"#D1D5DB",strokeDashArray:3},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}},axisBorder:{show:!1}}};function createChartTwo(e,o){var t={series:[{name:"This Day",data:[12,18,12,48,18,30,18,15,88,40,65,24,48]}],chart:{type:"area",width:"100%",height:264,sparkline:{enabled:!1},toolbar:{show:!1},padding:{left:0,right:0,top:0,bottom:0}},dataLabels:{enabled:!1},stroke:{curve:"straight",width:4,colors:[o],lineCap:"round"},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:3,position:"back",xaxis:{lines:{show:!1}},yaxis:{lines:{show:!0}},row:{colors:void 0,opacity:.5},column:{colors:void 0,opacity:.5},padding:{top:0,right:0,bottom:0,left:0}},fill:{type:"gradient",colors:[o],gradient:{shade:"light",type:"vertical",shadeIntensity:.5,gradientToColors:[`${o}00`],inverseColors:!1,opacityFrom:.6,opacityTo:.3,stops:[0,100]}},markers:{colors:[o],strokeWidth:3,size:0,hover:{size:10}},xaxis:{labels:{show:!1},categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}}},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}},tooltip:{x:{format:"dd/MM/yy HH:mm"}}};new ApexCharts(document.querySelector(`#${e}`),t).render()}(chart=new ApexCharts(document.querySelector("#defaultLineChart"),options)).render(),createChartTwo("zoomAbleLineChart","#487fff");options={series:[{name:"Desktops",data:[5,25,35,15,21,15,35,35,51]}],chart:{height:264,type:"line",colors:"#000",zoom:{enabled:!1},toolbar:{show:!1}},colors:["#487FFF"],dataLabels:{enabled:!0},stroke:{curve:"straight",width:4,color:"#000"},markers:{size:0,strokeWidth:3,hover:{size:8}},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:3,row:{colors:["#f3f3f3","transparent"],opacity:0}},markers:{colors:"#487FFF",strokeWidth:3,size:0,hover:{size:10}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],lines:{show:!1}},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}}};function createLineChart(e,o){var t={series:[{name:"This Day",data:[8,15,9,20,10,33,13,22,8,17,10,15]},{name:"Example",data:[8,24,18,40,18,48,22,38,18,30,20,28]}],chart:{type:"line",width:"100%",height:264,sparkline:{enabled:!1},toolbar:{show:!1},padding:{left:0,right:0,top:0,bottom:0}},colors:["#487FFF","#FF9F29"],dataLabels:{enabled:!1},stroke:{curve:"smooth",width:4,colors:["#FF9F29",o],lineCap:"round"},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:3,position:"back",xaxis:{lines:{show:!1}},yaxis:{lines:{show:!0}},row:{colors:void 0,opacity:.5},column:{colors:void 0,opacity:.5},padding:{top:0,right:0,bottom:0,left:0}},markers:{colors:["#FF9F29",o],strokeWidth:3,size:0,hover:{size:10}},xaxis:{labels:{show:!1},categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}}},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}},tooltip:{x:{format:"dd/MM/yy HH:mm"}},legend:{show:!1}};new ApexCharts(document.querySelector(`#${e}`),t).render()}(chart=new ApexCharts(document.querySelector("#lineDataLabel"),options)).render(),createLineChart("doubleLineChart","#487fff");options={series:[{data:[16,25,38,50,32,20,42,18,4,25,12,12],name:"Example"}],chart:{type:"line",height:270,toolbar:{show:!1}},stroke:{curve:"stepline"},colors:["#487FFF"],dataLabels:{enabled:!1},markers:{hover:{sizeOffset:4}},grid:{show:!0,borderColor:"#D1D5DB",strokeDashArray:3,position:"back"},xaxis:{labels:{show:!1},categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}}},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}}};(chart=new ApexCharts(document.querySelector("#stepLineChart"),options)).render();var chart;options={series:[{name:"This month",data:[12,6,22,18,38,16,40,8,35,18,35,22,50]}],chart:{height:264,type:"line",toolbar:{show:!1},zoom:{enabled:!1}},dataLabels:{enabled:!1},stroke:{curve:"smooth",colors:["#FF9F29"],width:4},fill:{type:"gradient",gradient:{shade:"dark",gradientToColors:["#0E53F4"],shadeIntensity:1,type:"horizontal",opacityFrom:1,opacityTo:1,stops:[0,100,100,100]}},markers:{size:0,strokeWidth:3,hover:{size:8}},tooltip:{enabled:!0,x:{show:!0},y:{show:!1},z:{show:!1}},grid:{row:{colors:["transparent","transparent"],opacity:.5},borderColor:"#D1D5DB",strokeDashArray:3},yaxis:{labels:{formatter:function(e){return"$"+e+"k"},style:{fontSize:"14px"}}},xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],tooltip:{enabled:!1},labels:{formatter:function(e){return e},style:{fontSize:"14px"}},axisBorder:{show:!1}}};(chart=new ApexCharts(document.querySelector("#gradientLineChart"),options)).render();
