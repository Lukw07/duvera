# Schránka Důvěry - Anonymous Messaging System

Bezpečná aplikace pro anonymní zasílání zpráv s podporou SMTP emailů přes Forpsi.

## Funkce

- 📝 Anonymní odesílání zpráv
- 📧 SMTP email notifikace přes Forpsi
- 🔒 Bezpečné šifrované spojení
- 🎨 Moderní responzivní design
- 🇨🇿 Česká lokalizace

## SMTP Konfigurace pro Forpsi

### 1. Nastavení emailového účtu

Před nasazením aplikace je potřeba:

1. **Vytvořit emailový účet** `noreply@zskamenicka.cz` na Forpsi
2. **Nastavit heslo** pro tento účet
3. **Ověřit funkčnost** účtu přes webmail

### 2. Konfigurace aplikace

Upravte soubor `config.php`:

```php
'smtp' => [
    'host' => 'smtp.forpsi.com',
    'port' => 587, // TLS port (doporučeno)
    'encryption' => 'tls',
    'username' => 'noreply@zskamenicka.cz',
    'password' => 'VAŠE_HESLO_ZDE', // ⚠️ Nastavte skutečné heslo
    'from_email' => 'noreply@zskamenicka.cz',
    'from_name' => 'Schránka Důvěry - ZŠ Kamenická',
],
'admin' => [
    'email' => 'admin@zskamenicka.cz', // Email pro příjem zpráv
    'name' => 'Administrátor'
]
```

### 3. Forpsi SMTP nastavení

- **SMTP server:** `smtp.forpsi.com`
- **Port:** `587` (TLS) nebo `465` (SSL)
- **Šifrování:** TLS nebo SSL (povinné)
- **Autentifikace:** Povinná (LOGIN/PLAIN)
- **Uživatelské jméno:** Celá emailová adresa
- **Heslo:** Heslo k emailovému účtu

### 4. Testování SMTP

Po nasazení navštivte: `https://your-domain.com/smtp-test.php`

⚠️ **Bezpečnost:** Smažte `smtp-test.php` po otestování!

## Nasazení na Coolify

### Požadavky

- PHP 8.2+
- Composer
- PHPMailer
- Funkční emailový účet na Forpsi

### Kroky nasazení

1. **Klonování repozitáře**

   ```bash
   git clone <repository-url>
   cd duvera
   ```

2. **Konfigurace SMTP**

   - Upravte `config.php` s vašimi SMTP údaji
   - Nastavte správné heslo pro `noreply@zskamenicka.cz`

3. **Nasazení v Coolify**

   - Vytvořte nový projekt
   - Vyberte "Git Repository"
   - Nastavte build pack na "Nixpacks"
   - Aplikace se automaticky sestaví s `composer install`

4. **Testování**
   - Navštivte `/smtp-test.php` pro test SMTP
   - Otestujte hlavní formulář
   - Smažte testovací soubory

## Struktura projektu

```
duvera/
├── index.php              # Hlavní aplikace
├── config.php             # SMTP a aplikační konfigurace
├── composer.json          # PHP závislosti
├── src/
│   └── EmailService.php   # SMTP email služba
├── nixpacks.toml          # Coolify build konfigurace
├── smtp-test.php          # Test SMTP (smazat po testování)
└── README.md
```

## Bezpečnostní poznámky

- ✅ Všechny vstupy jsou sanitizovány
- ✅ SMTP komunikace je šifrovaná (TLS/SSL)
- ✅ Hesla nejsou logována
- ✅ IP adresy jsou zaznamenávány pro bezpečnost
- ⚠️ Smažte testovací soubory po nasazení
- ⚠️ Používejte silná hesla pro emailové účty

## Řešení problémů

### SMTP chyby

1. **Authentication failed**

   - Zkontrolujte uživatelské jméno a heslo
   - Ověřte, že účet `noreply@zskamenicka.cz` existuje

2. **Connection refused**

   - Zkontrolujte port (587 pro TLS, 465 pro SSL)
   - Ověřte, že Forpsi neblokuje připojení

3. **Sender rejected**
   - Doména musí být hostována na Forpsi
   - Odesílatel musí odpovídat autentifikačnímu účtu

### Forpsi specifické limity

- **250 zpráv/minuta**
- **1000 zpráv/hodina**
- **20000 zpráv/den**
- **250 příjemců na zprávu**

## Podpora

Pro technickou podporu kontaktujte:

- Forpsi support: https://support.forpsi.com
- Dokumentace SMTP: https://support.forpsi.com/kb/a3156/

## Licence

MIT License
