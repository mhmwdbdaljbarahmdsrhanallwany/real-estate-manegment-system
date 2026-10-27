(function(window) {

    "use strict";


    /*
    |--------------------------------------------------------------------------
    | MapManager
    |--------------------------------------------------------------------------
    |
    | مدير عام للخرائط باستخدام Leaflet + MapTiler.
    |
    |--------------------------------------------------------------------------
    */

    const MapManager = {

        /*
        |--------------------------------------------------------------------------
        | تهيئة الخريطة
        |--------------------------------------------------------------------------
        */

        init: function(options) {

            const settings = Object.assign({

                mapElement: "map",

                latitudeInput: "latitude",

                longitudeInput: "longitude",

                mapUrlInput: "map_url",

                apiKey: "",

                defaultLatitude: 24.7136,

                defaultLongitude: 46.6753,

                defaultZoom: 5,

                selectedZoom: 15,

                searchPlaceholder: "ابحث عن مدينة أو حي أو شارع..."

            }, options || {});


            /*
            |--------------------------------------------------------------------------
            | عنصر الخريطة
            |--------------------------------------------------------------------------
            */

            const mapElement =
                document.getElementById(
                    settings.mapElement
                );


            if (!mapElement) {

                console.error(
                    "MapManager: map element not found."
                );

                return null;

            }


            /*
            |--------------------------------------------------------------------------
            | المتغيرات
            |--------------------------------------------------------------------------
            */

            let map = null;

            let marker = null;

            let searchTimeout = null;


            /*
            |--------------------------------------------------------------------------
            | حقول الموقع
            |--------------------------------------------------------------------------
            */

            const latitudeInput =
                document.getElementById(
                    settings.latitudeInput
                );


            const longitudeInput =
                document.getElementById(
                    settings.longitudeInput
                );


            const mapUrlInput =
                document.getElementById(
                    settings.mapUrlInput
                );


            /*
            |--------------------------------------------------------------------------
            | قراءة الإحداثيات المحفوظة
            |--------------------------------------------------------------------------
            */

            const savedLatitude =
                latitudeInput ?
                parseFloat(latitudeInput.value) :
                NaN;


            const savedLongitude =
                longitudeInput ?
                parseFloat(longitudeInput.value) :
                NaN;


            const hasSavedLocation =
                Number.isFinite(savedLatitude) &&
                Number.isFinite(savedLongitude);


            /*
            |--------------------------------------------------------------------------
            | الموقع الابتدائي
            |--------------------------------------------------------------------------
            */

            const initialLatitude =
                hasSavedLocation ?
                savedLatitude :
                settings.defaultLatitude;


            const initialLongitude =
                hasSavedLocation ?
                savedLongitude :
                settings.defaultLongitude;


            const initialZoom =
                hasSavedLocation ?
                settings.selectedZoom :
                settings.defaultZoom;


            /*
            |--------------------------------------------------------------------------
            | إنشاء الخريطة
            |--------------------------------------------------------------------------
            */

            map =
                L.map(mapElement)
                .setView(
                    [
                        initialLatitude,
                        initialLongitude
                    ],
                    initialZoom
                );


            /*
            |--------------------------------------------------------------------------
            | MapTiler
            |--------------------------------------------------------------------------
            */

            L.tileLayer(

                "https://api.maptiler.com/maps/streets-v4/{z}/{x}/{y}.png?key=" +
                encodeURIComponent(settings.apiKey),

                {

                    tileSize: 512,

                    zoomOffset: -1,

                    minZoom: 1,

                    attribution: '&copy; <a href="https://www.maptiler.com/copyright/" target="_blank">MapTiler</a> ' +
                        '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap contributors</a>',

                    crossOrigin: true

                }

            ).addTo(map);


            /*
            |--------------------------------------------------------------------------
            | تحديث بيانات الموقع
            |--------------------------------------------------------------------------
            */
            function updateLocation(
                latitude,
                longitude
            ) {

                latitude =
                    Number(latitude);

                longitude =
                    Number(longitude);


                /*
                |--------------------------------------------------------------------------
                | خط العرض
                |--------------------------------------------------------------------------
                */

                if (latitudeInput) {

                    latitudeInput.value =
                        latitude.toFixed(7);

                    latitudeInput.dispatchEvent(
                        new Event("input", {
                            bubbles: true
                        })
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | خط الطول
                |--------------------------------------------------------------------------
                */

                if (longitudeInput) {

                    longitudeInput.value =
                        longitude.toFixed(7);

                    longitudeInput.dispatchEvent(
                        new Event("input", {
                            bubbles: true
                        })
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | رابط الخريطة
                |--------------------------------------------------------------------------
                */

                if (mapUrlInput) {

                    mapUrlInput.value =
                        "https://www.google.com/maps?q=" +
                        latitude +
                        "," +
                        longitude;

                    mapUrlInput.dispatchEvent(
                        new Event("input", {
                            bubbles: true
                        })
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | إنشاء Marker
            |--------------------------------------------------------------------------
            */

            function setMarker(
                latitude,
                longitude
            ) {

                latitude =
                    Number(latitude);


                longitude =
                    Number(longitude);


                /*
                | إزالة Marker القديم
                */

                if (marker) {

                    map.removeLayer(marker);

                }


                /*
                | إنشاء Marker
                */

                marker =
                    L.marker(

                        [
                            latitude,
                            longitude
                        ],

                        {
                            draggable: true
                        }

                    ).addTo(map);


                /*
                | تحديث الحقول
                */

                updateLocation(
                    latitude,
                    longitude
                );


                /*
                |--------------------------------------------------------------------------
                | عند سحب Marker
                |--------------------------------------------------------------------------
                */

                marker.on(
                    "dragend",
                    function(event) {

                        const position =
                            event.target.getLatLng();


                        updateLocation(

                            position.lat,

                            position.lng

                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Marker للموقع المحفوظ
            |--------------------------------------------------------------------------
            */

            if (hasSavedLocation) {

                setMarker(
                    savedLatitude,
                    savedLongitude
                );

            }


            /*
            |--------------------------------------------------------------------------
            | النقر على الخريطة
            |--------------------------------------------------------------------------
            */

            map.on(
                "click",
                function(event) {

                    setMarker(

                        event.latlng.lat,

                        event.latlng.lng

                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | إنشاء Search Container
            |--------------------------------------------------------------------------
            */

            const searchContainer =
                L.DomUtil.create(
                    "div",
                    "organization-map-search"
                );


            /*
            |--------------------------------------------------------------------------
            | إنشاء مربع البحث باستخدام DOM
            |--------------------------------------------------------------------------
            */

            const searchInput =
                document.createElement(
                    "input"
                );


            searchInput.type =
                "text";


            searchInput.className =
                "map-location-search";


            searchInput.placeholder =
                settings.searchPlaceholder;


            searchInput.autocomplete =
                "off";


            /*
            |--------------------------------------------------------------------------
            | إنشاء حاوية النتائج
            |--------------------------------------------------------------------------
            */

            const resultsContainer =
                document.createElement(
                    "div"
                );


            resultsContainer.className =
                "organization-search-results";


            /*
            |--------------------------------------------------------------------------
            | إضافة العناصر إلى Search Container
            |--------------------------------------------------------------------------
            */

            searchContainer.appendChild(
                searchInput
            );


            searchContainer.appendChild(
                resultsContainer
            );


            /*
            |--------------------------------------------------------------------------
            | منع النقر داخل البحث من تحريك الخريطة
            |--------------------------------------------------------------------------
            */

            L.DomEvent.disableClickPropagation(
                searchContainer
            );


            L.DomEvent.disableScrollPropagation(
                searchContainer
            );


            /*
            |--------------------------------------------------------------------------
            | Search Control
            |--------------------------------------------------------------------------
            */

            const searchControl =
                L.control({
                    position: "topright"
                });


            searchControl.onAdd =
                function() {

                    return searchContainer;

                };


            searchControl.addTo(map);


            /*
            |--------------------------------------------------------------------------
            | البحث في MapTiler Geocoding API
            |--------------------------------------------------------------------------
            */

            searchInput.addEventListener(
                "input",
                function() {

                    const query =
                        this.value.trim();


                    /*
                    | إلغاء البحث السابق
                    */

                    clearTimeout(
                        searchTimeout
                    );


                    /*
                    | مسح النتائج إذا كان البحث قصيرًا
                    */

                    if (query.length < 2) {

                        resultsContainer.innerHTML =
                            "";

                        return;

                    }


                    /*
                    | تأخير البحث 300ms
                    */

                    searchTimeout =
                        setTimeout(
                            async function() {

                                try {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | رابط MapTiler Geocoding
                                    |--------------------------------------------------------------------------
                                    */

                                    const url =

                                        "https://api.maptiler.com/geocoding/" +

                                        encodeURIComponent(query) +

                                        ".json" +

                                        "?language=ar" +

                                        "&autocomplete=true" +

                                        "&limit=5" +

                                        "&key=" +

                                        encodeURIComponent(
                                            settings.apiKey
                                        );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | إرسال الطلب
                                    |--------------------------------------------------------------------------
                                    */

                                    const response =
                                        await fetch(url);


                                    if (!response.ok) {

                                        throw new Error(
                                            "فشل الاتصال بخدمة البحث"
                                        );

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | قراءة البيانات
                                    |--------------------------------------------------------------------------
                                    */

                                    const data =
                                        await response.json();


                                    /*
                                    | تنظيف النتائج القديمة
                                    */

                                    resultsContainer.innerHTML =
                                        "";


                                    /*
                                    |--------------------------------------------------------------------------
                                    | لا توجد نتائج
                                    |--------------------------------------------------------------------------
                                    */

                                    if (!data.features ||
                                        data.features.length === 0
                                    ) {

                                        const noResults =
                                            document.createElement(
                                                "div"
                                            );


                                        noResults.className =
                                            "organization-search-result";


                                        noResults.textContent =
                                            "لا توجد نتائج";


                                        resultsContainer.appendChild(
                                            noResults
                                        );


                                        return;

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | عرض النتائج
                                    |--------------------------------------------------------------------------
                                    */

                                    data.features.forEach(
                                        function(feature) {

                                            /*
                                            | التأكد من وجود الإحداثيات
                                            */

                                            if (!feature.geometry ||
                                                !feature.geometry.coordinates ||
                                                feature.geometry.coordinates.length < 2
                                            ) {

                                                return;

                                            }


                                            /*
                                            | إنشاء عنصر النتيجة
                                            */

                                            const result =
                                                document.createElement(
                                                    "div"
                                                );


                                            result.className =
                                                "organization-search-result";


                                            result.textContent =

                                                feature.place_name ||

                                                feature.text ||

                                                "نتيجة";


                                            /*
                                            |--------------------------------------------------------------------------
                                            | عند اختيار النتيجة
                                            |--------------------------------------------------------------------------
                                            */

                                            result.addEventListener(
                                                "click",
                                                function() {

                                                    const coordinates =
                                                        feature.geometry.coordinates;


                                                    const longitude =
                                                        Number(
                                                            coordinates[0]
                                                        );


                                                    const latitude =
                                                        Number(
                                                            coordinates[1]
                                                        );


                                                    /*
                                                    | نقل الخريطة
                                                    */

                                                    map.setView(

                                                        [
                                                            latitude,
                                                            longitude
                                                        ],

                                                        settings.selectedZoom

                                                    );


                                                    /*
                                                    | إنشاء Marker
                                                    */

                                                    setMarker(

                                                        latitude,

                                                        longitude

                                                    );


                                                    /*
                                                    | إخفاء النتائج
                                                    */

                                                    resultsContainer.innerHTML =
                                                        "";


                                                    /*
                                                    | وضع اسم المكان في البحث
                                                    */

                                                    searchInput.value =

                                                        feature.place_name ||

                                                        feature.text ||

                                                        "";

                                                }
                                            );


                                            /*
                                            | إضافة النتيجة
                                            */

                                            resultsContainer.appendChild(
                                                result
                                            );

                                        }
                                    );


                                } catch (error) {

                                    console.error(
                                        "Geocoding error:",
                                        error
                                    );


                                    /*
                                    | عرض رسالة الخطأ
                                    */

                                    resultsContainer.innerHTML =
                                        "";


                                    const errorResult =
                                        document.createElement(
                                            "div"
                                        );


                                    errorResult.className =
                                        "organization-search-result";


                                    errorResult.textContent =
                                        "حدث خطأ أثناء البحث";


                                    resultsContainer.appendChild(
                                        errorResult
                                    );

                                }

                            },

                            300

                        );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | إعادة حساب حجم الخريطة
            |--------------------------------------------------------------------------
            */

            function refreshMapSize() {

                if (!map) {

                    return;

                }


                setTimeout(
                    function() {

                        map.invalidateSize();

                    },
                    200
                );

            }


            /*
            |--------------------------------------------------------------------------
            | مراقبة ظهور قسم الخريطة
            |--------------------------------------------------------------------------
            */

            const formSection =
                mapElement.closest(
                    ".form-section"
                );


            if (formSection) {

                const observer =
                    new MutationObserver(
                        function() {

                            if (!formSection.classList.contains(
                                    "d-none"
                                )) {

                                refreshMapSize();

                            }

                        }
                    );


                observer.observe(
                    formSection, {
                        attributes: true,
                        attributeFilter: [
                            "class"
                        ]
                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | تحديث حجم الخريطة عند تغيير حجم الشاشة
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                "resize",
                refreshMapSize
            );


            /*
            |--------------------------------------------------------------------------
            | تحديث أولي لحجم الخريطة
            |--------------------------------------------------------------------------
            */

            refreshMapSize();


            /*
            |--------------------------------------------------------------------------
            | إرجاع كائن الخريطة
            |--------------------------------------------------------------------------
            */

            return {

                map: map,

                setMarker: setMarker,

                updateLocation: updateLocation,

                refreshSize: refreshMapSize

            };

        }

    };


    /*
    |--------------------------------------------------------------------------
    | إتاحة MapManager عالميًا
    |--------------------------------------------------------------------------
    */

    window.MapManager =
        MapManager;


})(window);