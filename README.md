# Laravel Lucky Wheel (`gwebas/laravel-lucky-wheel`)

🎲 Interaktyvus, animuotas ir lengvai konfigūruojamas Laimės Rato (Lucky Wheel) paketas skirtas Laravel el. komercijos ir interneto svetainių projektams. Suteikia galimybę vartotojams sukti ratą ir laimėti nuolaidų kodus ar nemokamą pristatymą, o administratoriams – patogiai valdyti prizus, tikimybių svorius, dizaino temas ir el. pašto surinkimo rinkodarą.

---

## ✨ Pagrindinės Savybės

- **3 Palaikomos Temos:** Moderni (`default`), Šventinė Kalėdų (`christmas`) su snaigėmis ir Ryški Vasaros (`summer`).
- **Apsauga nuo sukčiavimo (Anti-Cheat):** Ribojimas pagal Vartotojo ID, IP adresą, naršyklės Fingerprint bei slapukus (Cookies) pagal nustatytą laikotarpį (`cooldown_hours`).
- **Lanksti El. pašto politika:** 
  - `none`: Nereikalaujama (Svečiai gali sukti iškart).
  - `before_spin`: Reikalaujama įvesti el. paštą prieš sukant ratą.
  - `after_win`: Reikalaujama įvesti el. paštą po sukimui laimėjus prizą, kad jį atsiimti / pamatyti kodą.
- **Svorio (Tikimybės) Algoritmas:** Administratorius nustato kiekvieno prizo svorį (`probability_weight`). Kuo didesnis svoris, tuo didesnė laimėjimo tikimybė.
- **Automatinis Kodų Generavimas:** Paketas automatiškai sukuria unikalų promo kodą (pvz., `WHEEL-ABC123` arba `FREE-SHIP-XYZ1`) ir susieja jį su jūsų `discount_codes` sistema.
- **Daugiakalbiškumas (i18n):** Pilna anglų (EN) ir lietuvių (LT) kalbų palaikymo sistema per JSON ir tradicinius Laravel vertimo failus.

---

## 🚀 Įdiegimas (Installation)

### 1 Variantas: Naudojant lokalią arba privačią GitHub repozitoriją (Path / VCS)
Jei paketą laikote projekto aplanke `packages/gwebas/laravel-lucky-wheel` arba privačioje GitHub repozitorijoje, savo pagrindinio projekto `composer.json` faile pridėkite repozitorijos įrašą:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/gwebas/laravel-lucky-wheel"
        }
    ]
}
```
*(Pastaba: Jei keliate į GitHub, pakeiskite `type` į `"vcs"`, o `url` į `"https://github.com/jusu-vartotojas/laravel-lucky-wheel"`).*

Tuomet paleiskite komandą:
```bash
composer require gwebas/laravel-lucky-wheel
```

### 2 Variantas: Publikavimas į Packagist.org (Viešas)
Jei užregistruosite savo repozitoriją [Packagist.org](https://packagist.org), kituose projektuose įdiegimas bus vos viena komanda:
```bash
composer require gwebas/laravel-lucky-wheel
```

---

## 🛠️ Konfigūravimas ir Migracijos

Įdiegę paketą, nopubuotokite konfigūraciją, migracijas ir vertimus į pagrindinį projektą:

```bash
php artisan vendor:publish --tag=lucky-wheel-config
php artisan vendor:publish --tag=lucky-wheel-migrations
php artisan vendor:publish --tag=lucky-wheel-lang
```

Paleiskite duomenų bazės migracijas (sukuriamos `lucky_wheel_settings`, `lucky_wheel_prizes`, `lucky_wheel_spins` lentelės):
```bash
php artisan migrate
```

---

## 📖 Naudojimas (Usage)

### 1. Rato atvaizdavimas svetainėje (Frontend integration)

Jūsų pagrindiniame išdėstymo šablone (pvz., `resources/views/layouts/app.blade.php` arba `frontend.blade.php`) prieš uždarant `</body>` žymę įdėkite Blade komponentą:

```blade
<x-lucky-wheel::wheel />
```

Komponentas automatiškai patikrins, ar laimės ratas yra įjungtas administravimo panele, pritaikys pasirinktą temą ir parodys plaukiojantį mygtuką apatiniame kairiam/dešiniam kampe.

### 2. Administratoriaus valdymo pultas (Admin Dashboard)

Valdymo pultas pasiekiamas maršrutu:
```text
GET /admin/lucky-wheel
```
*(Maršrutas saugomas `web` ir `auth` middleware).*

Čia administratorius gali:
- Vienu paspaudimu **Įjungti / Išjungti** ratą visoje svetainėje.
- Pakeisti aktyvią **Dizaino temą** (`default`, `christmas`, `summer`).
- Nustatyti **El. pašto reikalavimą** (`none`, `before_spin`, `after_win`).
- Keisti **Pakartotinio sukimo ribojimo** valandas (`cooldown_hours`).
- Kurti, redaguoti bei šalinti prizus ir reguliuoti jų tikimybės svorius.

---

## 🌐 Vertimai ir Kalbų nustatymai (Localization)

Paketas automatiškai naudoja aktyvią jūsų svetainės kalbą (`app()->getLocale()`).
Palaikomos kalbos pagal nutylėjimą: **EN (English)** ir **LT (Lietuvių)**.

Jei norite priverstinai nustatyti konkrečią kalbą tik laimės ratui, atsidarykite `config/lucky-wheel.php` ir pakeiskite reikšmę:
```php
'locale' => 'lt', // Arba 'en', arba null (automatiniam aptikimui)
```

Jei norite redaguoti ar pridėti naujų kalbų vertimus jūsų projekte, redaguokite failus aplanke:
`resources/lang/vendor/lucky-wheel/`

---

## 📦 Kaip paruošti paketo publikavimui (Packagist / GitHub Guide)

Kai norėsite panaudoti šį paketą kituose 2 projektuose per `composer require`:
1. Sukurkite naują GitHub repozitoriją (pvz., `laravel-lucky-wheel`).
2. Perkėlkite visą `packages/gwebas/laravel-lucky-wheel` turinį į naujos repozitorijos šaknį ir padarykite `git push`.
3. Sukurkite Git žymę (tag), pvz.: `git tag v1.0.0 && git push --tags`.
4. Eikite į [Packagist.org](https://packagist.org), prisijunkite su GitHub ir spauskite **Submit**. Įklijuokite savo repozitorijos nuorodą.
5. Viskas! Kituose projektuose galėsite tiesiog rašyti `composer require gwebas/laravel-lucky-wheel`.

---

## 📝 Licencija
Šis paketas yra atviro kodo programinė įranga, platinama pagal MIT licenciją.
