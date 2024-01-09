# Projektna naloga pri predmetu Telekomunikacijski protokoli
## Smart home

Izdelava centraliziranega sistema za nadzor pametne hiše.

## Namen
izdelava centraliziranega sistema za nadzor pametne hiše. Sistem sestavljajo pametne naprave (vrata, luči, toplotna črpalka) in uporabniški vmesnik (spletna splikacija) glavno stikališče je Raspberry Pi 3B+, na katerem je nameščen spletni strežnik Apache, podatkovna baza Mysql, Grafana (mogoče), MQTT broker Mosquito,...Uporabnik lahko preko spletnega vmesnika prižiga in ugaša luči, spremlja podatke ogravnja/hlajenja, odpira in zapira vrata. Sistem tudi omogoča samodejen prižig in ugašanje luči ter samodejno pošiljanje alarmov v zvezi z ogravanjem.

## Lastnosti

- Centraliziran sistem (glavna naprava Raspberry Pi in več podrejenih naprav)
- Spremljanje delovanja toplotne črpalke
- Vklop ali izkliop luči
- Spremljanje stanja vodnega zalogovnika
- Odpiranje in zapiranje vrat
- varnostne kamere
- ...

## Naprave

- Raspberry Pi 3B+
- Arduino Nano
- Raspberry Zero W

## Tehnologije
| Tehnologija | namen |
| ------ | ------ |
| MQTT | Komunikacija med napravami - pošiljanje podatkov |
| Apache web server | Hosting spletne strani |
| InfluxDB | Sharnjevanje podatkov |
| API endpoints | Pridobivanje podatkov o vremenu |
| HTML/CSS/JS | Kodo za spletno stran |
| Python | Koda za obdelovanje podatkov |
| PHP | Kodo za spletno stran |
| Linux | Uporabljen OS |

Nekaj knjižnic, ki sem jih uporabil pri izdelavi:

- [InfluxDB]
- [php-mqtt]
- [jQuery]
- ...

[//]:

   [php-mqtt]: <https://github.com/php-mqtt/client>
   [jQuery]: <http://jquery.com>
   [InfluxDB]: <https://github.com/influxdata/influxdb-python>

