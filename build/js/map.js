campusMap = L.map('mapid').setView([53.227846, -0.547053], 15);

L.tileLayer('https://api.mapbox.com/styles/v1/{user}/{style}/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    maxZoom: 18,
    id: 'burnsy.1jka5oj8',
    user: 'mapbox',
    style: 'streets-v9',
    accessToken: 'pk.eyJ1IjoiYnVybnN5IiwiYSI6ImNpdTE4ODhlMDAwMDQybm5yNGR6bzVidGIifQ.nQiLqpwmAMOU_rgA6Mbtaw',
}).addTo(campusMap);