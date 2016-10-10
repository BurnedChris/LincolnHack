communityMap = L.map('mapid').setView([53.227846, -0.547053], 15);

L.tileLayer('https://api.mapbox.com/styles/v1/{user}/{style}/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: '',
    maxZoom: 18,
    id: 'burnsy.1jka5oj8',
    user: 'mapbox',
    style: 'streets-v9',
    accessToken: 'pk.eyJ1IjoiYnVybnN5IiwiYSI6ImNpdTE4ODhlMDAwMDQybm5yNGR6bzVidGIifQ.nQiLqpwmAMOU_rgA6Mbtaw',
}).addTo(communityMap);

// Fetch 15 fake profiles.
$.getJSON("api/fetch_faux_data?n=15", function (data) {
    L.geoJson(data, {
        pointToLayer: function (feature, latlng) {
            var marker = L.marker(latlng);
            marker.bindPopup('<div class="popup__image--avatar"><img src="' + feature.properties.Avatar + '"> </div>' +
                '<div class="popup__container--information"><div class="popup__infomation"><div class="popup__name">@' + feature.properties.User + '</div>' +
                '<div class="popup__catagory">Category: ' + feature.properties.Category + '</div>' +
                '<div class="popup__date">Date: ' + feature.properties.Date + '</div></div></div>' +
                '<div class="popup__description">"' + feature.properties.Description + '"</div>' +
                '<div class="popup__image"><img src="' + feature.properties.Image + '"> </div>' +
                '<a class="popup__button" href="' + feature.properties.Link + '"><div class="popup__link">More Details</div></a>');
            return marker;
        }
    }, {
        maxWidth: 600,
        autoPan: true
    }).addTo(communityMap);
});

// Fetch profiles.
$.getJSON("api/fetch_data", function (data) {
    L.geoJson(data, {
        pointToLayer: function (feature, latlng) {
            var marker = L.marker(latlng);
            marker.bindPopup('<div class="popup__image--avatar"><img src="' + feature.properties.Avatar + '"> </div>' +
                '<div class="popup__container--information"><div class="popup__infomation"><div class="popup__name">@' + feature.properties.User + '</div>' +
                '<div class="popup__catagory">Category: ' + feature.properties.Category + '</div>' +
                '<div class="popup__date">Date: ' + feature.properties.Date + '</div></div></div>' +
                '<div class="popup__description">"' + feature.properties.Description + '"</div>' +
                '<div class="popup__image"><img src="' + feature.properties.Image + '"> </div>' +
                '<a class="popup__button" href="' + feature.properties.Link + '"><div class="popup__link">More Details</div></a>');
            return marker;
        }
    }, {
        maxWidth: 600,
        autoPan: true
    }).addTo(communityMap);
});