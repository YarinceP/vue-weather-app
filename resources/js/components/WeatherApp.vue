<template>
    <div class="text-white mb-8">
        <div class="places-input text-gray-800">
            <input type="search" id="address" class="form-control shadow-lg" placeholder="¿A dónde vamos?"/>
            <p hidden>Selected: <strong id="address-value">none</strong></p>

        </div>
        <div class="weather-container font-sans w-128 max-w-lg overflow-hidden rounded-lg bg-gray-900 shadow-lg mt-4">

            <div class="current-weather flex items-center justify-between px-6 py-8">
                <div class="flex items-center">
                    <div class="">
                        <div class="text-6xl font-semibold">{{currentTemperature.actual}}°C</div>
                        <div>Sensación térmica {{currentTemperature.feels}} °C</div>
                        <div>{{ today }}</div>
                    </div>
                    <div class="mx-5">
                        <div class="font-semibold">{{capitalizeFirstLetter(currentTemperature.summary)}}</div>
                        <div>{{location.name}}</div>
                    </div>
                </div>
                <div>
                    <img :src="icon" alt="">
                </div>
            </div>
            <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
                <div v-for="(day , index) in daily.filter((day, index) => index < 5 )" :key="day.dt"
                     class="flex items-center"
                     :class="{ 'mt-8': index > 0}">
                    <div class="w-1/6 text-lg text-gray-200">{{toDayOfWeek(day.dt)}}</div>
                    <div class="w-4/6 px-4 flex items-center ">
                        <div>
                            <img :src="getIcon(day.weather[0].icon)" alt="" width="60" height="60">
                        </div>
                        <div class="ml-3">{{capitalizeFirstLetter(day.weather[0].description)}}</div>
                    </div>
                    <div class="w-1/6 text-right">
                        <div>{{ Math.round(day.temp.max)}}°C</div>
                        <div>{{Math.round(day.temp.min)}}°C</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        mounted() {

            const placesAutocomplete = places({
                appId: 'plPV8EXI12DS',
                apiKey: '41799a907c4cbca19bf652f768dc6f61',
                container: document.querySelector('#address')
            }).configure({
                type: 'city',
                aroundLatLngViaIP: false,
            });

            const $address = document.querySelector('#address-value');
            placesAutocomplete.on('change', (e) => {
                $address.textContent = e.suggestion.value
                console.log(e)
                this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
                this.location.lat = e.suggestion.latlng.lat
                this.location.lon = e.suggestion.latlng.lng
            });

            placesAutocomplete.on('clear', function () {
                $address.textContent = 'none';
            });

            this.fetchData()
        },
        watch: {
            location: {
                handler(newValue, oldValue) {
                    this.fetchData()
                },
                deep: true
            }
        },
        data() {
            return {
                icon: '',
                currentTemperature: {
                    actual: '',
                    feels: '',
                    summary: '',
                    icon: '',
                    dt: '',
                },
                daily: [],
                today: moment().lang('es').format("LL"),
                location: {
                    name: 'La Ceiba, Honduras',
                    lat: 15.790899,
                    lon: -86.786048,
                }
            }
        },
        methods: {
            fetchData() {
                fetch(`/api/weather?lat=${this.location.lat}&lon=${this.location.lon}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        this.currentTemperature.actual = Math.round(data.current.temp)
                        this.currentTemperature.feels = Math.round(data.current.feels_like)
                        this.currentTemperature.summary = data.current.weather[0].description
                        this.currentTemperature.icon = data.current.weather[0].icon
                        this.currentTemperature.dt = data.current.dt
                        this.icon = this.getIcon(this.currentTemperature.icon)
                        this.daily = data.daily
                    })
            },
            getIcon(name) {
                return `http://openweathermap.org/img/wn/${name}@2x.png`
            },
            toDayOfWeek(timestamp) {
                const newDate = new Date(timestamp * 1000)
                const days = ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'];

                return days[newDate.getDay()]
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }
    }
</script>
